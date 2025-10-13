@if($cartItemsCount <= 0)
    <p class="bg-soul text-white p-2" style="margin: 0; font-weight: 500 !important;">Your cart is currently empty!.</p>
@else
    @foreach($cartItems as $itemId => $item)
        <li class="single-cart-item cart-item-{{ $itemId }}">
            <a href="#" class="photo">
                <img src="{{ asset('storage/products/' . $item['image']) }}" alt="{{ $item['smell'] }}">
            </a>
            <div class="cart-item-details">
                <h6><a href="{{ route('product', ['asin' => $item['asin'], 'slug' => Str::slug($item['slug']) ]) }}">{{ $item['smell'] }}</a></h6>
                <p><span id="cart-item-quantity-{{ $itemId }}">{{ $item['quantity'] }}</span> x <span class="price">E£{{ $item['price'] }}</span></p>
            </div>
            <div class="cart-item-close">
                <span class="lnr lnr-cross"></span>
            </div>
        </li>
    @endforeach

    <li class="cart-total">
        <span>Total: E£<strong class="cart-total-price"> {{ $totalPrice }} </strong></span>
        <a href="{{ route('cart.view') }}" class="btn-cart bg-soul text-dark">View Cart</a>
    </li>
@endif

