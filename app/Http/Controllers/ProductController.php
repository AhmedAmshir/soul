<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductVariation;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

class ProductController extends Controller {

    protected $cartService;

    public function __construct(CartService $cartService) {
        $this->cartService = $cartService;
    }
    
    public function show($slug, $asin) {

        $variation = ProductVariation::with('product')->where('asin', $asin)->firstOrFail();

        if ($slug !== Str::slug($variation->slug)) {
            abort(404);
        }

        $cartData = $this->cartService->getCartData();

        return view('partials.product', [
            'variation' => $variation,
            'cartItems' => $cartData['cartItems'],
            'totalPrice' => $cartData['totalPrice'],
            'cartItemsCount' => $cartData['cartItemsCount'],
        ]);
    }

}
