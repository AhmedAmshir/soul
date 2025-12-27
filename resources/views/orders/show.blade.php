@extends('orders.details-layout')

@section('content')
    <h1 class="my-4">Order Details</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($showCouponNotification) && $showCouponNotification && $couponCode)
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg style="width: 24px; height: 24px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                <div style="flex: 1;">
                    <strong style="font-size: 16px; display: block; margin-bottom: 4px;">📧 Send Coupon to Customer</strong>
                    <p style="margin: 0; font-size: 14px;">
                        The customer has not used their coupon code yet. Please send them the coupon code <strong>{{ $couponCode }}</strong> when this order is shipped. 
                        The coupon is still valid and ready to be sent.
                    </p>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="opacity: 0.8;"></button>
        </div>
    @endif

    <!-- Order Summary -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Order Summary</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y H:i:s') }}</p>
                    <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge 
                            @if($order->status === 'pending') bg-secondary
                            @elseif($order->status === 'placed') bg-primary
                            @elseif($order->status === 'out_for_delivery') bg-warning
                            @elseif($order->status === 'shipped') bg-info
                            @elseif($order->status === 'delivered') bg-success
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </p>
                    <p><strong>Coupon Code:</strong> 
                        @if($order->coupon)
                            <span style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 4px 12px; border-radius: 6px; font-weight: 600; display: inline-block;">
                                {{ $order->coupon->code }}
                            </span>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                        
                        <p><strong>Shipping Method:</strong> {{ ucfirst($order->shipping_method) }}</p>
                        <p><strong>Shipping Cost:</strong> <span id="shipping">E£ {{ config('app.shipping_fee') }}</span></p>
                        <p><strong>Discount Amount:</strong> <span style="color: red;">- E£ {{ number_format($order->discount_amount, 0) }}</span></p>
                        <p><strong>Subtotal Amount:</strong> E£ {{ number_format($order->subtotal_amount, 0) }}</p>
                        <p><strong>Total Amount:</strong> <span style="color: #10b981; font-weight: bold; font-size: 1.2rem;">E£ {{ number_format($order->total_amount, 0) }}</span></p>
                    </div>
            </div>
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->variation->name }} - {{ $item->variation->smell }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>E£{{ number_format($item->price, 0) }}</td>
                                <td>E£{{ number_format($item->quantity * $item->price, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Shipping Details -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Shipping Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Full Name:</strong> {{ $order->shippingAddress->full_name }}</p>
            <p><strong>Phone Number:</strong> {{ $order->shippingAddress->phone_number }}</p>
            <p><strong>Email:</strong> {{ $order->shippingAddress->email }}</p>
            <p><strong>Address:</strong> {{ $order->shippingAddress->address }} - {{ $order->shippingAddress->country }} - {{ $order->shippingAddress->city }} - {{ $order->shippingAddress->governorate }}</p>
        </div>
    </div>

    <!-- Update Order Status Form -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Update Order Status</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <select name="status" id="status" class="form-control" required>
                        @foreach(App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ $order->status === $status->value ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-3">Update Order Status</button>
            </form>
        </div>
    </div>

    <!-- Back to Orders List -->
    <div class="text-center">
        <a href="{{ route('orders.index') }}" class="btn btn-soul">Back to Orders</a>
    </div>
@endsection