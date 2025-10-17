@extends('layouts.app')

@section('title', 'Shopping Cart - Soul')
@section('description', 'Review your selected essential oil diffusers before checkout. Secure shopping cart with easy quantity adjustments.')

<script>
    var incrementQuantityUrl = "{{ route('cart.update') }}";
    var decrementQuantityUrl = "{{ route('cart.remove') }}";
    var clearCartUrl = "{{ route('cart.clear') }}";
</script>

@section('content')
    <!-- Cart Section -->
    <section class="cart-section">
        <div class="container">
            <!-- Cart Header -->
            <div class="cart-header">
                <h1 class="cart-title">Shopping Cart</h1>
                <div class="cart-breadcrumb">
                    <span>Home</span>
                    <svg class="breadcrumb-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                    <span class="current">Cart</span>
                </div>
            </div>
            
            @if(empty($cartItems))
                <!-- Empty Cart State -->
                <div class="empty-cart">
                    <div class="empty-cart-content">
                        <div class="empty-cart-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                        <h2 class="empty-cart-title">Your cart is empty</h2>
                        <p class="empty-cart-description">Looks like you haven't added any items to your cart yet. Start exploring our collection of premium essential oil diffusers.</p>
                        <a href="{{ route('homepage') }}" class="btn btn-primary btn-lg">
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9,22 9,12 15,12 15,22"/>
                            </svg>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="cart-layout">
                    <!-- Cart Items -->
                    <div class="cart-items-section">
                        <div class="cart-items-header">
                            <h2 class="cart-items-title">Cart Items (<span id="cart-items-count">{{ count($cartItems) }}</span>)</h2>
                            <button class="clear-cart-btn" onclick="clearCart()">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                </svg>
                                Clear Cart
                            </button>
                        </div>
                        
                        <div class="cart-items" id="cartContent">
                            @foreach($cartItems as $itemId => $item)
                                <div class="cart-item-card">
                                    <div class="cart-item-image">
                                        <img src="{{ asset('storage/products/' . $item['image']) }}" alt="{{ $item['smell'] }}" />
                                        <div class="item-badge">
                                            <span>{{ $item['size_ml'] }}ml</span>
                                        </div>
                                    </div>
                                    
                                    <div class="cart-item-details">
                                        <div class="item-header">
                                            <h3 class="item-name">{{ $item['product_name'] }}</h3>
                                            <button class="remove-item-btn" onclick="removeFromCart('{{ $itemId }}')" aria-label="Remove item">
                                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M18 6L6 18"/>
                                                    <path d="M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <p class="item-description">{{ $item['smell'] }}</p>
                                        
                                        <!-- <div class="item-specs">
                                            <span class="spec-item">
                                                <svg class="spec-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                                Premium Quality
                                            </span>
                                            <span class="spec-item">
                                                <svg class="spec-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M9 12l2 2 4-4"/>
                                                    <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                                                    <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                                                </svg>
                                                Natural Ingredients
                                            </span>
                                        </div> -->
                                        
                                        <div class="item-price-section">
                                            <div class="price-info">
                                                <span class="price-label">Price per unit:</span>
                                                <span class="item-price">E£ {{ number_format($item['price'], 0) }}</span>
                                            </div>
                                            <div class="total-price">
                                                <span class="total-label">Total:</span>
                                                <span class="item-total" data-item-id="{{ $itemId }}">E£ {{ number_format($item['price'] * $item['quantity'], 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="cart-item-controls">
                                        <div class="quantity-section">
                                            <!-- <label class="quantity-label">Quantity</label> -->
                                            <div class="quantity-controls">
                                                <button type="button" class="quantity-btn quantity-decrease" onclick="decrementCartQuantity('{{ $itemId }}')" aria-label="Decrease quantity">
                                                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path d="M5 12h14"/>
                                                    </svg>
                                                </button>
                                                <input 
                                                    name="quantity" 
                                                    type="number" 
                                                    value="{{ $item['quantity'] }}" 
                                                    min="1" 
                                                    max="10" 
                                                    data-product-id="{{ $itemId }}" 
                                                    onchange="updateCartQuantity('{{ $itemId }}', this.value)"
                                                    class="quantity-input"
                                                    aria-label="Quantity"
                                                >
                                                <button type="button" class="quantity-btn quantity-increase" onclick="incrementCartQuantity('{{ $itemId }}')" aria-label="Increase quantity">
                                                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path d="M12 5v14"/>
                                                        <path d="M5 12h14"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- <div class="quantity-limit">Max: 10 units</div> -->
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary-section">
                        <div class="cart-summary-card">
                            <h3 class="summary-title">Order Summary</h3>
                            
                            <div class="summary-details">
                                <div class="summary-row">
                                    <span class="summary-label">Subtotal ({{ count($cartItems) }} items)</span>
                                    <span class="summary-value" id="subtotal">E£ {{ number_format($totalPrice, 0) }}</span>
                                </div>
                                
                                <div class="summary-row">
                                    <span class="summary-label">Shipping</span>
                                    <span class="summary-value" id="shipping">E£ 70</span>
                                    <!-- <span class="summary-value shipping-free">
                                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M9 12l2 2 4-4"/>
                                        </svg>
                                        E£ 70 
                                    </span> -->
                                </div>
                                
                                <!-- <div class="summary-row">
                                    <span class="summary-label">Tax</span>
                                    <span class="summary-value">Included</span>
                                </div> -->
                                
                                <!-- <div class="summary-divider"></div> -->
                                
                                <div class="summary-row total-row">
                                    <span class="summary-label">Total</span>
                                    <span class="summary-value total-value" id="total">E£ {{ number_format($totalPrice + 70, 0) }}</span>
                                </div>
                            </div>
                            
                            <div class="checkout-actions">
                                <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-lg checkout-btn">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                                        <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                                    </svg>
                                    Proceed to Checkout
                                </a>
                                
                                <!-- <div class="security-badges">
                                    <div class="security-badge">
                                        <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                        </svg>
                                        <span>Secure Checkout</span>
                                    </div>
                                    <div class="security-badge">
                                        <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M9 12l2 2 4-4"/>
                                            <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                                        </svg>
                                        <span>Money Back Guarantee</span>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        
                        <!-- Continue Shopping -->
                        <div class="continue-shopping">
                            <a href="{{ route('homepage') }}" class="continue-shopping-btn">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection