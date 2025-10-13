<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

class CheckoutController extends Controller {

    protected $cartService;

    public function __construct(CartService $cartService) {

        $this->cartService = $cartService;
    }

    public function checkout() {

        $cartData = $this->cartService->getCartData();

        $cartItems = $cartData['cartItems'];
        $totalPrice = $cartData['totalPrice'];
        $cartItemsCount = $cartData['cartItemsCount'];
        $totalQuantity = array_sum(array_column($cartData['cartItems'], 'quantity'));

        return view('partials.checkout', compact('cartItems', 'totalPrice', 'cartItemsCount', 'totalQuantity'));
    }

    public function processCheckout(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:credit_card,paypal,cash_on_delivery',
        ]);

        // Get the cart
        $cart = Cart::where('user_id', Auth::id())
                    ->orWhere('session_id', session()->getId())
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'total_price' => $cart->items->sum(function ($item) {
                return $item->variation->price * $item->quantity;
            }),
        ]);

        // Add order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variation_id' => $item->product_variation_id,
                'quantity' => $item->quantity,
                'price' => $item->variation->price,
            ]);
        }

        // Clear the cart
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('order.confirmation', $order->id)->with('success', 'Order placed successfully!');
    }
}
