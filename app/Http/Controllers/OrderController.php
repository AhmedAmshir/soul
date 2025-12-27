<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\CouponService;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use Illuminate\Validation\Rule;

class OrderController extends Controller {


    protected $cartService;
    protected $couponService;

    public function __construct(CartService $cartService, CouponService $couponService) {
        $this->cartService = $cartService;
        $this->couponService = $couponService;
    }

    public function index(Request $request) {

        $orders = Order::with(['user', 'shippingAddress', 'items.variation.product'])
                    ->latest();

        if ($request->has('status') && $request->status !== '') {
            $orders->where('status', $request->status);
        }
        
        $orders = $orders->paginate(25);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order) {

        $order->load(['user', 'shippingAddress', 'items.variation.product', 'coupon']);

        // Check if coupon hasn't been used and should show notification
        // Notification will appear on first order and continue on subsequent orders until coupon is used
        $showCouponNotification = false;
        $couponCode = null;

        if ($order->shippingAddress) {
            $phoneNumber = $order->shippingAddress->phone_number;

            // Get the coupon code to send
            $couponCode = $this->couponService->getCouponCodeToSend();
            
            if ($couponCode) {
                // Check if coupon has reached the maximum usage limit (250)
                if (!$this->couponService->hasReachedUsageLimit($couponCode)) {
                    // Check if coupon exists and hasn't been used by this phone number
                    $coupon = Coupon::where('code', $couponCode)->first();
                    if ($coupon && !$coupon->isUsedByPhone($phoneNumber)) {
                        $showCouponNotification = true;
                    }
                }
            }
        }

        return view('orders.show', compact('order', 'showCouponNotification', 'couponCode'));
    }

    public function updateStatus(Request $request, Order $order) {

        $request->validate([
            'status' => ['required', 'string', Rule::in(OrderStatus::all())],
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function placeOrder(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+?[0-9\s\-]+$/',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:255',
            'apartment_suite' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'governorate' => 'required|string|max:255',
            'postcode' => 'string|regex:/^[A-Za-z0-9\s\-]+$/',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $cart = $this->cartService->getCartData();

        $phoneNumber = trim($request->phone);
        $phoneNumber = preg_replace('/[^\d+]/', '', $phoneNumber);
        $phoneNumber = preg_replace('/^(\+2|2|\+20|20)/', '', $phoneNumber);
        $phoneNumber = ltrim($phoneNumber, '+');

        DB::beginTransaction();

        try {
            $address = Address::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'full_name' => $request->name,
                'phone_number' => $phoneNumber,
                'email' => $request->email,
                'address' => $request->shipping_address,
                'city' => $request->city,
                'governorate' => $request->governorate,
                'postcode' => $request->postcode,
                'country' => 'Egypt',   
                'apartment_suite' => $request->apartment_suite
            ]);

            $totalQuantity = 0;
            $totalPrice = 0;
            foreach ($cart['cartItems'] as $itemVariation => $item) {

                $totalQuantity += $item['quantity'];
                $totalPrice += $item['quantity'] * $item['price'];
            }

            // Handle coupon if provided
            $discountAmount = 0;
            $couponId = null;
            $subtotalAmount = $totalPrice;

            if ($request->filled('coupon_code')) {
                $couponResult = $this->couponService->calculateDiscount(
                    $request->coupon_code,
                    $phoneNumber,
                    $totalPrice
                );

                if ($couponResult['success']) {
                    $discountAmount = $couponResult['discount'];
                    $couponId = $couponResult['coupon_id'];
                } else {
                    // Coupon validation failed, return error
                    return redirect()->back()
                        ->withInput()
                        ->with('error', $couponResult['message']);
                }
            }

            $finalTotal = $subtotalAmount - $discountAmount + config('app.shipping_fee'); // Add shipping cost

            $order = Order::create([
                'order_number' => 'SO' . Str::upper(Str::random(10)),
                'user_id' => Auth::check() ? Auth::id() : null,
                'shipping_address_id' => $address->id,
                'billing_address_id' => $address->id,
                'shipping_method' => 'courier',
                'shipping_cost' => config('app.shipping_fee'),
                'payment_method' => $request->payment_method,
                'subtotal_amount' => $subtotalAmount,
                'discount_amount' => $discountAmount,
                'coupon_id' => $couponId,
                'total_amount' => $finalTotal,
            ]);

            foreach ($cart['cartItems'] as $itemVariation => $item) {

                $variation = ProductVariation::find($item['variation_id']);
                if (!$variation) {
                    throw new \Exception("Product variation not found: " . $item['variation_id']);
                }

                if ($variation->stock < $item['quantity']) {
                    throw new \Exception('Sorry, insufficient stock for ' . $variation->description);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variation_id' => $item['variation_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $variation->decrement('stock', $item['quantity']);
            }

            // Record coupon usage if coupon was applied
            if ($couponId && $discountAmount > 0) {
                CouponUsage::create([
                    'coupon_id' => $couponId,
                    'phone_number' => $phoneNumber,
                    'order_id' => $order->id,
                    'discount_amount' => $discountAmount,
                    'used_at' => now(),
                ]);
            }

            DB::commit();

            $cookie = Cookie::forget('cart');

            return redirect()->route('order.confirmation', $order->order_number)->withCookie($cookie)->with('success', 'Your order has been placed successfully! Order Number: ' . $order->order_number);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Order placement failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Order placement failed: ' . $e->getMessage());
        }
    }

    public function showConfirmation($order_number) {

        $order = Order::where('order_number', $order_number)
            ->with(['shippingAddress', 'items.variation.product'])->firstOrFail();

        if (empty($order)) {
            abort(404);
        }
        $cartItemsCount = 0;
        return view('partials.order-confirmation', compact('order', 'cartItemsCount'));
    }
}