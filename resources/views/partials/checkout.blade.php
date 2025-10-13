@extends('layouts.app')

@section('title', 'Checkout - Soul')
@section('description', 'Complete your essential oil diffuser purchase with our secure checkout. Fast shipping and satisfaction guaranteed.')

@section('content')
    <!-- Checkout Section -->
    <section class="checkout-section">
        <div class="container">
            <!-- Checkout Header -->
            <div class="checkout-header">
                <h1 class="checkout-title">Checkout</h1>
                <div class="checkout-breadcrumb">
                    <span>Cart</span>
                    <svg class="breadcrumb-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                    <span class="current">Checkout</span>
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
                        <p class="empty-cart-description">Add some items to your cart before proceeding to checkout.</p>
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
                <!-- Checkout Progress -->
                <div class="checkout-progress">
                    <div class="progress-steps">
                        <div class="progress-step active">
                            <div class="step-number">1</div>
                            <div class="step-label">Information</div>
                        </div>
                        <div class="progress-line"></div>
                        <div class="progress-step">
                            <div class="step-number">2</div>
                            <div class="step-label">Shipping</div>
                        </div>
                        <div class="progress-line"></div>
                        <div class="progress-step">
                            <div class="step-number">3</div>
                            <div class="step-label">Payment</div>
                        </div>
                    </div>
                </div>

                <div class="checkout-layout">
                    <!-- Checkout Form -->
                    <div class="checkout-form-section">
                        <div class="checkout-form-card">
                        @if(session('error'))
                                <div class="alert alert-danger">
                                    <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                    <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="checkoutForm" action="{{ route('order.place') }}" method="POST">
                            @csrf
                                
                                <!-- Step 1: Contact Information -->
                                <div class="form-step active" data-step="1">
                                    <div class="step-header">
                                        <h2 class="step-title">Contact Information</h2>
                                        <p class="step-description">We'll use this information to send you order updates</p>
                                    </div>
                                    
                                    <div class="form-grid">
                                <div class="form-group">
                                            <label for="email" class="form-label">
                                                <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                                    <polyline points="22,6 12,13 2,6"/>
                                                </svg>
                                                Email Address
                                            </label>
                                            <input type="email" id="email" name="email" class="form-input" placeholder="your@email.com" required>
                                </div>
                                        
                                <div class="form-group">
                                            <label for="phone" class="form-label">
                                                <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                                </svg>
                                                Phone Number
                                            </label>
                                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+20 123 456 7890" pattern="^\+?[0-9\s\-]+$" title="Please enter a valid phone number" required>
                                </div>
                            </div>

                                    <div class="step-actions">
                                        <button type="button" class="btn btn-secondary btn-primary btn-lg" onclick="goToStep(2)">
                                            Continue to Shipping
                                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 2: Shipping Address -->
                                <div class="form-step" data-step="2">
                                    <div class="step-header">
                                        <h2 class="step-title">Shipping Address</h2>
                                        <p class="step-description">Where should we deliver your order?</p>
                                </div>
                                    
                                <div class="form-group">
                                        <label for="name" class="form-label">
                                            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            Full Name
                                        </label>
                                        <input type="text" id="name" name="name" class="form-input" placeholder="Enter your full name" required>
                                </div>
                                    
                                    <div class="form-group">
                                        <label for="shipping_address" class="form-label">
                                            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            Street Address
                                        </label>
                                        <textarea id="shipping_address" name="shipping_address" class="form-textarea" rows="3" placeholder="Enter your street address" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="apartment_suite" class="form-label">
                                            <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                                <polyline points="9,22 9,12 15,12 15,22"/>
                                            </svg>
                                            Apartment, Suite, etc. (Optional)
                                        </label>
                                        <input type="text" id="apartment_suite" name="apartment_suite" class="form-input" placeholder="Apt, suite, floor, etc.">
                                    </div>
                                    
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="city" class="form-label">
                                                <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                    <circle cx="12" cy="10" r="3"/>
                                                </svg>
                                                City
                                            </label>
                                            <input type="text" id="city" name="city" class="form-input" placeholder="Enter city" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="governorate" class="form-label">
                                                <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                                </svg>
                                                Governorate
                                            </label>
                                            <select id="governorate" name="governorate" class="form-select" required>
                                            <option value="">Select Governorate</option>
                                            <option value="cairo">Cairo</option>
                                            <option value="giza">Giza</option>
                                            <option value="alexandria">Alexandria</option>
                                            <option value="aswan">Aswan</option>
                                            <option value="asyut">Asyut</option>
                                            <option value="beheira">Beheira</option>
                                            <option value="beni-suef">Beni Suef</option>
                                            <option value="dakahlia">Dakahlia</option>
                                            <option value="damietta">Damietta</option>
                                            <option value="faiyum">Faiyum</option>
                                            <option value="gharbia">Gharbia</option>
                                            <option value="ismailia">Ismailia</option>
                                            <option value="kafr-el-sheikh">Kafr El Sheikh</option>
                                            <option value="luxor">Luxor</option>
                                            <option value="matruh">Matruh</option>
                                            <option value="minya">Minya</option>
                                            <option value="monufia">Monufia</option>
                                            <option value="new-valley">New Valley</option>
                                            <option value="north-sinai">North Sinai</option>
                                            <option value="port-said">Port Said</option>
                                            <option value="qalyubia">Qalyubia</option>
                                            <option value="qena">Qena</option>
                                            <option value="red-sea">Red Sea</option>
                                            <option value="sharqia">Sharqia</option>
                                            <option value="sohag">Sohag</option>
                                            <option value="suez">Suez</option>
                                        </select>
                                    </div>
                                        
                                    <div class="form-group">
                                            <label for="postcode" class="form-label">
                                                <svg class="label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                    <circle cx="12" cy="10" r="3"/>
                                                </svg>
                                                Postal Code
                                            </label>
                                            <input type="text" id="postcode" name="postcode" class="form-input" placeholder="12345">
                                        </div>
                                    </div>
                                    
                                    <div class="step-actions">
                                        <button type="button" class="btn btn-outline" onclick="goToStep(1)">
                                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                                            </svg>
                                            Back
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg" onclick="goToStep(3)">
                                            Continue to Payment
                                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 3: Payment Method -->
                                <div class="form-step" data-step="3">
                                    <div class="step-header">
                                        <h2 class="step-title">Payment Method</h2>
                                        <p class="step-description">Choose how you'd like to pay for your order</p>
                                    </div>
                                    
                                    <div class="payment-methods">
                                        <div class="payment-option">
                                            <input type="radio" name="payment_method" id="cash_on_delivery" value="cod" class="payment-radio" required checked>
                                            <label for="cash_on_delivery" class="payment-label">
                                                <div class="payment-icon">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path d="M12 1v6l3-3 3 3-3 3-3-3v6"/>
                                                        <path d="M5 13v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6"/>
                                                        <path d="M9 19h6"/>
                                                    </svg>
                                                </div>
                                                <div class="payment-info">
                                                    <h3>Cash on Delivery</h3>
                                                    <p>Pay when your order arrives at your doorstep</p>
                                                </div>
                                                <div class="payment-badge">
                                                    <span>Recommended</span>
                                                </div>
                                            </label>
                            </div>

                                        <div class="payment-option disabled">
                                            <input type="radio" name="payment_method" id="credit_card" value="card" class="payment-radio" disabled>
                                            <label for="credit_card" class="payment-label">
                                                <div class="payment-icon">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                                        <line x1="1" y1="10" x2="23" y2="10"/>
                                                    </svg>
                                                </div>
                                                <div class="payment-info">
                                                    <h3>Credit/Debit Card</h3>
                                                    <p>Coming soon - Secure online payment</p>
                                                </div>
                                                <div class="payment-badge">
                                                    <span>Soon</span>
                                                </div>
                                        </label>
                                        </div>
                                    </div>
                                    
                                    <div class="step-actions">
                                        <button type="button" class="btn btn-outline" onclick="goToStep(2)">
                                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                                            </svg>
                                            Back
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg complete-order-btn">
                                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M9 12l2 2 4-4"/>
                                                <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                                                <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                                            </svg>
                                            Complete Order
                                        </button>
                                    </div>
                                </div>
                            </form>
                            </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="checkout-summary-section">
                        <div class="order-summary-card">
                            <h3 class="summary-title">Order Summary</h3>
                            
                        <div class="order-items" id="orderItems">
                            @foreach($cartItems as $item)
                                <div class="order-item">
                                    <div class="order-item-image">
                                            <img src="{{ asset('storage/products/' . $item['image']) }}" alt="{{ $item['smell'] }}" />
                                            <div class="item-quantity-badge">{{ $item['quantity'] }}</div>
                                        </div>
                                        <div class="order-item-details">
                                            <h4 class="item-name">{{ $item['smell'] }}</h4>
                                            <p class="item-specs">{{ $item['size_ml'] }}ml • Premium Quality</p>
                                            <div class="item-price">E£ {{ number_format($item['price'] * $item['quantity'], 0) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="order-totals">
                            <div class="total-row">
                                    <span class="total-label">Subtotal ({{ count($cartItems) }} items)</span>
                                    <span class="total-value" id="orderSubtotal">E£ {{ number_format($totalPrice, 0) }}</span>
                            </div>
                                
                            <div class="total-row">
                                    <span class="total-label">Shipping</span>
                                    <span class="total-value" id="shipping">E£ 70</span>

                                    <!-- <span class="total-value shipping-free" id="shipping">
                                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M9 12l2 2 4-4"/>
                                        </svg>
                                        Free
                                    </span> -->
                                </div>
                                
                                <!-- <div class="total-row">
                                    <span class="total-label">Tax</span>
                                    <span class="total-value">Included</span>
                                </div> -->
                                
                                <!-- <div class="total-divider"></div> -->
                                
                                <div class="total-row total-final">
                                    <span class="total-label">Total</span>
                                    <span class="total-value total-amount" id="orderTotal">E£ {{ number_format($totalPrice + 70, 0) }}</span>
                                </div>
                            </div>
                            
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
                        
                        <!-- Back to Cart -->
                        <div class="back-to-cart">
                            <a href="{{ route('cart.view') }}" class="back-to-cart-btn">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection