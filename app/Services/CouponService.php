<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponService
{
    /**
     * Validate a coupon code for a given phone number and subtotal
     * 
     * @param string $code
     * @param string $phoneNumber
     * @param float $subtotal
     * @return array ['valid' => bool, 'message' => string, 'discount' => float|null, 'coupon' => Coupon|null]
     */
    public function validateCoupon(string $code, string $phoneNumber, float $subtotal): array
    {
        // Find the coupon
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'The coupon code is invalid.',
                'discount' => null,
                'coupon' => null,
            ];
        }

        // Check if coupon is active
        if (!$coupon->is_active) {
            return [
                'valid' => false,
                'message' => 'This coupon is not active.',
                'discount' => null,
                'coupon' => $coupon,
            ];
        }

        // Check if coupon is valid (date range)
        if (!$coupon->isValid()) {
            return [
                'valid' => false,
                'message' => 'This coupon has expired or is not yet valid.',
                'discount' => null,
                'coupon' => $coupon,
            ];
        }

        // Normalize phone number
        $normalizedPhone = Coupon::normalizePhoneNumber($phoneNumber);

        // Check if this is the first order - coupon cannot be used on first order
        if ($this->checkIfFirstOrder($phoneNumber)) {
            return [
                'valid' => false,
                'message' => 'The coupon code is invalid.',
                'discount' => null,
                'coupon' => $coupon,
            ];
        }

        // Check if coupon has already been used by this phone number
        if ($coupon->isUsedByPhone($normalizedPhone)) {
            return [
                'valid' => false,
                'message' => 'The coupon code has already been used.',
                'discount' => null,
                'coupon' => $coupon,
            ];
        }

        // Check minimum order amount
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum order amount of E£ ' . number_format($coupon->min_order_amount, 0) . ' required.',
                'discount' => null,
                'coupon' => $coupon,
            ];
        }

        // Check if coupon usage limit (250) has been reached
        $totalUsages = $coupon->usages()->count();
        if ($totalUsages >= 250) {
            return [
                'valid' => false,
                'message' => 'The coupon code is invalid.',
                'discount' => null,
                'coupon' => $coupon,
            ];
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discount,
            'coupon' => $coupon,
        ];
    }

    /**
     * Calculate discount amount for a coupon code (before order creation)
     * 
     * @param string $code
     * @param string $phoneNumber
     * @param float $subtotal
     * @return array ['success' => bool, 'message' => string, 'discount' => float|null, 'coupon_id' => int|null]
     */
    public function calculateDiscount(string $code, string $phoneNumber, float $subtotal): array
    {
        $validation = $this->validateCoupon($code, $phoneNumber, $subtotal);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
                'discount' => null,
                'coupon_id' => null,
            ];
        }

        return [
            'success' => true,
            'message' => $validation['message'],
            'discount' => $validation['discount'],
            'coupon_id' => $validation['coupon']->id,
        ];
    }

    /**
     * Apply coupon to an order (after order is created)
     * 
     * @param string $code
     * @param string $phoneNumber
     * @param int $orderId
     * @return array ['success' => bool, 'message' => string, 'discount' => float|null]
     */
    public function applyCoupon(string $code, string $phoneNumber, int $orderId): array
    {
        $order = Order::findOrFail($orderId);
        
        // Validate coupon
        $validation = $this->validateCoupon($code, $phoneNumber, $order->subtotal_amount ?? $order->total_amount);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
                'discount' => null,
            ];
        }

        $coupon = $validation['coupon'];
        $discount = $validation['discount'];
        $normalizedPhone = Coupon::normalizePhoneNumber($phoneNumber);

        DB::beginTransaction();

        try {
            // Calculate subtotal if not set
            $subtotal = $order->subtotal_amount ?? ($order->total_amount - $order->shipping_cost);
            
            // Update order with coupon
            $order->update([
                'coupon_id' => $coupon->id,
                'discount_amount' => $discount,
                'subtotal_amount' => $subtotal,
                'total_amount' => $subtotal - $discount + $order->shipping_cost,
            ]);

            // Record coupon usage
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'phone_number' => $normalizedPhone,
                'order_id' => $orderId,
                'discount_amount' => $discount,
                'used_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount' => $discount,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to apply coupon: ' . $e->getMessage(),
                'discount' => null,
            ];
        }
    }

    /**
     * Check if this is the first order for a phone number
     * 
     * @param string $phoneNumber
     * @return bool
     */
    public function checkIfFirstOrder(string $phoneNumber): bool
    {
        $normalizedPhone = Coupon::normalizePhoneNumber($phoneNumber);

        // Get all addresses with matching phone numbers (normalized)
        $addressIds = Address::all()->filter(function ($address) use ($normalizedPhone) {
            $addressPhone = Coupon::normalizePhoneNumber($address->phone_number);
            return $addressPhone === $normalizedPhone;
        })->pluck('id');

        if ($addressIds->isEmpty()) {
            return true; // No addresses found, so it's the first order
        }

        // Check if there are any previous orders with these addresses
        $previousOrdersCount = Order::whereIn('shipping_address_id', $addressIds)->count();

        return $previousOrdersCount === 0;
    }

    /**
     * Get phone number from order
     * 
     * @param Order $order
     * @return string|null
     */
    public function getPhoneNumberFromOrder(Order $order): ?string
    {
        if ($order->shippingAddress) {
            return $order->shippingAddress->phone_number;
        }

        return null;
    }

    /**
     * Check if customer should receive coupon (first order and order is shipped)
     * 
     * @param Order $order
     * @return bool
     */
    public function shouldSendCoupon(Order $order): bool
    {
        $phoneNumber = $this->getPhoneNumberFromOrder($order);
        
        if (!$phoneNumber) {
            return false;
        }

        // Check if this is the first order
        if (!$this->checkIfFirstOrder($phoneNumber)) {
            return false;
        }

        // Check if order is shipped or out for delivery
        $shippedStatuses = ['shipped', 'out_for_delivery'];
        if (!in_array($order->status, $shippedStatuses)) {
            return false;
        }

        return true;
    }

    /**
     * Get coupon code to send to customer
     * This assumes you have a predefined coupon code
     * You can modify this to fetch from database or config
     * 
     * @return string|null
     */
    public function getCouponCodeToSend(): ?string
    {
        // TODO: Replace with your actual coupon code or fetch from database/config
        // For now, returning null - you'll need to set this
        return config('coupons.first_order_code', null);
    }

    /**
     * Check if coupon has reached the maximum usage limit (250)
     * 
     * @param string $couponCode
     * @return bool
     */
    public function hasReachedUsageLimit(string $couponCode): bool
    {
        $coupon = Coupon::where('code', $couponCode)->first();
        
        if (!$coupon) {
            return true; // If coupon doesn't exist, consider limit reached
        }

        $totalUsages = $coupon->usages()->count();
        return $totalUsages >= 250;
    }
}

