<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    public function getCartData() {

        $cartItems = [];
        $totalPrice = 0;
        $cartItemsCount = 0;
        if (Auth::check()) {

            $cart = Cart::where('user_id', Auth::id())
                        ->where('session_id', session()->getId())
                        ->first();
    
            if ($cart) {
                $cartItems = $cart->items()->with('variation.product')->get();
                $cartItemsCount = $cartItems->count();
                $totalPrice = $cartItems->sum(function ($item) {
                    return $item->variation->price * $item->quantity;
                });
            }
        } else {

            $cartData = json_decode(Cookie::get('cart'), true) ?? [];
   
            if (!empty($cartData['items'])) {
                $cartItems = $cartData['items'];
                $cartItemsCount = count($cartData['items']);
                $totalPrice = $cartData['total_price'];
            }
        }

        return [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalPriceWithShipping' => $totalPrice + 70,
            'shippingCost' => 70,
            'cartItemsCount' => $cartItemsCount,
        ];
    }
}