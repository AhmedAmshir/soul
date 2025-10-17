<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Services\CartService;

class CartController extends Controller {

    public function addToCart(Request $request) {

        $request->validate([
            'variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variation = ProductVariation::findOrFail($request->variation_id);

        if($variation->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock',
            ], 400);
        }

        $cartItems = [];
        $cartItemsCount = 0;
        $totalPrice = 0;

        if (Auth::check()) {
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id(),
                'session_id' => session()->getId(),
            ]);

            $cartItem = CartItem::firstOrNew([
                'cart_id' => $cart->id,
                'product_variation_id' => $variation->id,
            ]);

            $cartItem->quantity += $request->quantity;
            $cartItem->save();

            $cartItems = $cart->items()->with('variation.product')->get();
            $cartItemsCount = $cartItems->count();
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->variation->price * $item->quantity;
            });

            $cartData = [
                'items' => $cartItems->map(function ($item) {
                    return [
                        'variation_id' => $item->product_variation_id,
                        'quantity' => $item->quantity,
                        'name' => $item->variation->product->name,
                        'price' => $item->variation->price,
                        'name' => $item->variation->name,
                        'size_ml' => $item->variation->size_ml,
                        'description' => $item->variation->description,
                        'asin' => $item->variation->asin,
                        'slug' => $item->variation->slug,
                        'image' => $item->variation->image,
                    ];
                })->toArray(),
                'total_items' => $cart->items->sum('quantity'),
                'total_price' => $totalPrice,
            ];

            $cookie = Cookie::make('cart', json_encode($cartData), 60 * 24 * 30);
        } else {

            $cartData = json_decode(Cookie::get('cart'), true) ?? [];

            $cartData['items'][$variation->id] = [
                'variation_id' => $variation->id,
                'quantity' => ($cartData['items'][$variation->id]['quantity'] ?? 0) + $request->quantity,
                'name' => $variation->product->name,
                'price' => $variation->price,
                'smell' => $variation->smell,
                'size_ml' => $variation->size_ml,
                'description' => $variation->description,
                'asin' => $variation->asin,
                'slug' => $variation->slug,
                'image' => $variation->image,
            ];

            $cartData['total_items'] = count($cartData['items']);
            $cartData['total_price'] = array_reduce($cartData['items'], function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            $cookie = Cookie::make('cart', json_encode($cartData), 60 * 24 * 30);

            $cartItems = $cartData['items'];
            $cartItemsCount = $cartData['total_items'];
            $totalPrice = $cartData['total_price'];
        }

        $cartHtml = view('partials.cart-items', [
            'cartItems' => $cartItems,
            'cartItemsCount' => $cartItemsCount,
            'totalPrice' => $totalPrice,
        ])->render();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'cart_total_items' => $cartItemsCount,
            'cart_total_price' => $totalPrice,
            'cart_total_count' => $cartItemsCount,
            'cart_html' => $cartHtml,
        ])->withCookie($cookie); 
    }

    public function viewCart(CartService $cartService) {

        $cartData = $cartService->getCartData();

        $cartItems = $cartData['cartItems'];
        $totalPrice = $cartData['totalPrice'];
        $cartItemsCount = count($cartItems); // Use count of items array
        $totalQuantity = array_sum(array_column($cartData['cartItems'], 'quantity'));

        return view('partials.cart', compact('cartItems', 'totalPrice', 'cartItemsCount', 'totalQuantity'));
    }

    public function updateCartItem(Request $request) {

        $request->validate([
            'product_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $variation = ProductVariation::findOrFail($request->product_id);

        if ($variation->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock',
            ], 400);
        }

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found.',
                ], 404);
            }

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_variation_id', $variation->id)
                ->first();
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart.',
                ], 404);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();
    
            $cartItems = $cart->items()->with('variation.product')->get();
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->variation->price * $item->quantity;
            });
    
            $itemTotalPrice = $cartItem->variation->price * $cartItem->quantity;
        } else {

            $cartData = json_decode(Cookie::get('cart'), true) ?? [];

            if (isset($cartData['items'][$variation->id])) {
                $cartData['items'][$variation->id]['quantity'] = $request->quantity;
    
                $cartData['total_items'] = array_sum(array_column($cartData['items'], 'quantity'));
                $cartData['total_price'] = array_reduce($cartData['items'], function ($carry, $item) {
                    return $carry + ($item['price'] * $item['quantity']);
                }, 0);
    
                $cookie = Cookie::make('cart', json_encode($cartData), 60 * 24 * 30);
                
                $totalPrice = $cartData['total_price'];
                $itemTotalPrice = $cartData['items'][$variation->id]['price'] * $cartData['items'][$variation->id]['quantity'];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart.',
                ], 404);
            }
        }
    
        $response = [
            'success' => true,
            'cart_total_items' => Auth::check() ? $cart->items->sum('quantity') : ($cartData['total_items'] ?? 0),
            'cart_total_price' => $totalPrice,
            'cart_total_count' => count($cartData['items']),
            'item_quantity' => $request->quantity,
            'item_total_price' => $itemTotalPrice,
        ];

        if (isset($cookie)) {
            return response()->json($response)->withCookie($cookie);
        }

        return response()->json($response); 
    }

    public function removeCartItem(Request $request, CartItem $cartItem = null) {
        
        $request->validate([
            'product_id' => 'required|exists:product_variations,id',
        ]);
    
        $variation = ProductVariation::findOrFail($request->product_id);
   
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found.',
                ], 404);
            }

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_variation_id', $variation->id)
                ->first();
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart.',
                ], 404);
            }

            $cartItem->delete();
    
            $cartItems = $cart->items()->with('variation.product')->get();
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->variation->price * $item->quantity;
            });
    
            $cartItemsCount = $cartItems->sum('quantity');
        } else {
            
            $cartData = json_decode(Cookie::get('cart'), true) ?? [];
    
            if (isset($cartData['items'][$variation->id])) {
                unset($cartData['items'][$variation->id]);
    
                $cartData['total_items'] = array_sum(array_column($cartData['items'], 'quantity'));
                $cartData['total_price'] = array_reduce($cartData['items'], function ($carry, $item) {
                    return $carry + ($item['price'] * $item['quantity']);
                }, 0);
    
                $cookie = Cookie::make('cart', json_encode($cartData), 60 * 24 * 30);
    
                $totalPrice = $cartData['total_price'];
                $cartItemsCount = $cartData['total_items'];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart.',
                ], 404);
            }
        }
    
        $response = [
            'success' => true,
            'cart_total_items' => $cartItemsCount,
            'cart_total_price' => $totalPrice,
            'cart_total_count' => $cartItemsCount,
        ];

        if (isset($cookie)) {
            return response()->json($response)->withCookie($cookie);
        }

        return response()->json($response);
    }

    public function clearCart() {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found.',
                ], 404);
            }

            $cart->items()->delete();
            
            $response = [
                'success' => true,
                'cart_total_items' => 0,
                'cart_total_price' => 0,
                'cart_total_count' => 0,
            ];

            return response()->json($response);
        } else {
            $cartData = [
                'items' => [],
                'total_items' => 0,
                'total_price' => 0,
            ];

            $cookie = Cookie::make('cart', json_encode($cartData), 60 * 24 * 30);
            
            $response = [
                'success' => true,
                'cart_total_items' => 0,
                'cart_total_price' => 0,
                'cart_total_count' => 0,
            ];

            return response()->json($response)->withCookie($cookie);
        }
    }
    
}
