@extends('layouts.app')

@section('content')
<div class="order-confirmation-section">
    <div class="container">
        <!-- Success Header -->
        <div class="confirmation-header">
            <div class="success-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" fill="#10b981"/>
                    <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="confirmation-title">Order Confirmed!</h1>
            <p class="confirmation-subtitle">Thank you for your purchase. Your order has been successfully placed.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="confirmation-content">
            <!-- Order Summary Card -->
            <div class="order-summary-card">
                <div class="card-header">
                    <h3 class="card-title">Order Summary</h3>
                    <div class="order-number">#{{ $order->order_number }}</div>
                </div>
                
                <div class="order-details">
                    <div class="detail-row">
                        <span class="detail-label">Order Date</span>
                        <span class="detail-value">{{ $order->created_at->format('F j, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="status-badge status-{{ strtolower($order->status) }}">
                            @switch($order->status)
                                @case(App\Enums\OrderStatus::PLACED->value)
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    @break
                                @case(App\Enums\OrderStatus::OUT_FOR_DELIVERY->value)
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    @break
                                @case(App\Enums\OrderStatus::DELIVERED->value)
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    @break
                            @endswitch
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Method</span>
                        <span class="detail-value">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Shipping Method</span>
                        <span class="detail-value">{{ ucfirst($order->shipping_method) }}</span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-section">
                    <!-- <h4 class="section-title">Order Items</h4> -->
                    <div class="order-items">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ asset('storage/products/' . $item->variation->image) }}" alt="{{ $item->variation->product->name }}">
                                </div>
                                <div class="item-details">
                                    <h5 class="item-name">{{ $item->variation->product->name }}</h5>
                                    <p class="item-variation">{{ $item->variation->description }}</p>
                                    <div class="item-meta">
                                        <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                        <span class="item-price">E£{{ number_format($item->price, 0) }}</span>
                                    </div>
                                </div>
                                <div class="item-total">
                                    E£{{ number_format($item->quantity * $item->price, 0) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Delivery Address Card -->
                <div class="delivery-address-card">
                    <div class="card-header">
                        <h3 class="card-title">Delivery Address</h3>
                        <div class="address-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="address-details">
                        <div class="address-field">
                            <span class="field-label">Full Name</span>
                            <span class="field-value">{{ $order->shippingAddress->full_name }}</span>
                        </div>
                        <div class="address-field">
                            <span class="field-label">Phone Number</span>
                            <span class="field-value">{{ $order->shippingAddress->phone_number }}</span>
                        </div>
                        <div class="address-field">
                            <span class="field-label">Email</span>
                            <span class="field-value">{{ $order->shippingAddress->email }}</span>
                        </div>
                        <div class="address-field">
                            <span class="field-label">Address</span>
                            <span class="field-value">{{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->governorate }}, {{ $order->shippingAddress->country }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Totals Widget -->
                <div class="order-totals-widget">
                    <div class="card-header">
                        <h3 class="card-title">Order Total</h3>
                        <div class="total-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="order-totals">
                        <div class="total-row">
                            <span class="total-label">Subtotal</span>
                            <span class="total-value">E£ {{ number_format($order->total_amount, 0) }}</span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">Shipping</span>
                            <span class="total-value" id="shipping">E£ 70</span>
                        </div>
                        <div class="total-row total-final">
                            <span class="total-label">Total</span>
                            <span class="total-value">E£ {{ number_format($order->total_amount + 70, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="confirmation-actions">
            <a href="{{ route('homepage') }}" class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2"/>
                    <polyline points="9,22 9,12 15,12 15,22" stroke="currentColor" stroke-width="2"/>
                </svg>
                Continue Shopping
            </a>
            <!-- <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2"/>
                    <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" stroke="currentColor" stroke-width="2"/>
                </svg>
                View Order Details
            </a> -->
        </div>
    </div>
</div>
@endsection