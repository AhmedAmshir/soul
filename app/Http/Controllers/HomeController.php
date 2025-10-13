<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

class HomeController extends Controller {

    public function index(CartService $cartService) {

        $products = Product::with('variations')->get();

        $cartData = $cartService->getCartData();

        return view('homepage.index', [
            'products' => $products,
            'cartItems' => $cartData['cartItems'],
            'totalPrice' => $cartData['totalPrice'],
            'cartItemsCount' => $cartData['cartItemsCount'],
        ]);
    }
}
