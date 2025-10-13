<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return redirect()->route('homepage');
});

Route::get('/home', [HomeController::class, 'index'])->name('homepage');

Route::get('/product/{slug}/vn/{asin}', [ProductController::class, 'show'])->name('product');

Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCartItem'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeCartItem'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::get('/order/confirmation/{order_number}', [OrderController::class, 'showConfirmation'])->name('order.confirmation');
