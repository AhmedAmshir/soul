<?php

namespace App\Http\Controllers;

use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Validate coupon code during checkout
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'phone' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $result = $this->couponService->validateCoupon(
            $request->code,
            $request->phone,
            $request->subtotal
        );

        if ($result['valid']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'discount' => $result['discount'],
                'coupon_id' => $result['coupon']->id,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'discount' => null,
        ], 400);
    }
}

