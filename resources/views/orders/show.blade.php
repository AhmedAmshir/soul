@extends('orders.details-layout')

@section('content')
    <h1 class="my-4">Order Details</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
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
                </div>
                <div class="col-md-6">
                        <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                        <p><strong>Shipping Method:</strong> {{ ucfirst($order->shipping_method) }}</p>
                        <p><strong>Shipping Cost:</strong> <span id="shipping">E£ 70</span></p>
                        <p><strong>Total Amount:</strong> E£ {{ number_format($order->total_amount, 0) }}</p>
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