@extends('layouts.app')

@section('title', 'Luxury Reed Diffuser - Soul')
@section('description', 'Premium reed diffuser with natural essential oils. Choose from our collection of carefully crafted scents for your home.')

<script>
    var addToCartUrl = "{{ route('cart.add') }}";
    var productId = "{{ $variation->id ?? 'SO54582585' }}";
    var productPrice = "{{ $variation->price ?? 850 }}";
    var productStock = "{{ $variation->stock ?? 50 }}";
</script>

@section('content')
    <div class="product-page">
        <div class="container">
            <div class="product-layout">
                <!-- Product Gallery -->
                <div class="product-gallery">
                    <div class="main-image">
                        <img id="mainProductImage" src="{{ asset('storage/products/' . $variation->image) }}" alt="Luxury Reed Diffuser" />
                    </div>
                    <div class="thumbnail-gallery">

                        <div class="thumbnail active" data-image="{{ asset('storage/products/' . $variation->image) }}">
                            <img src="{{ asset('storage/products/' . $variation->image) }}" alt="Amber Diffuser" />
                        </div>
                        @php
                            $image = explode('.', $variation->image);
                            $image_name = $image[0].'-1.'.$image[1];
                        @endphp

                        <div class="thumbnail" data-image="{{ asset('storage/products/' . $image_name) }}">
                            <img src="{{ asset('storage/products/' . $image_name) }}" alt="Amber Diffuser" />
                        </div>
                        <div class="thumbnail" data-image="{{ asset('storage/products/bottle-side-1.png') }}">
                            <img src="{{ asset('storage/products/bottle-side-1.png') }}" alt="Amber Diffuser" />
                        </div>
                        <div class="thumbnail" data-image="{{ asset('storage/products/bottle-side-2.png') }}">
                            <img src="{{ asset('storage/products/bottle-side-2.png') }}" alt="Amber Diffuser" />
                        </div>
                        <div class="thumbnail" data-image="{{ asset('storage/products/bottle-side-3.png') }}">
                            <img src="{{ asset('storage/products/bottle-side-3.png') }}" alt="Amber Diffuser" />
                        </div>
                    </div>
                </div>

                <input type="hidden" id="product-id" value="{{ $variation->id }}">
                <input type="hidden" id="product-price" value="{{ $variation->price }}">
                <input type="hidden" id="product-stock" value="{{ $variation->stock }}">

                <!-- Product Information -->
                <div class="product-info">
                    <h1 class="product-title">{{ $variation->smell }}</h1>

                    <!-- Selected Scent Display -->
                    <div class="selected-scent" id="selectedScentDisplay">
                        <div class="scent-header">
                            <h3 id="selectedScentName">{{ $variation->smell }}</h3>
                            <div class="scent-price" id="selectedScentPrice">E£ {{ $variation->price }}</div>
                            <!-- <div class="selected-badge">
                                <span id="selectedQuantity">1</span> Selected
                            </div> -->
                        </div>
                        <p id="selectedScentDescription">{{ $variation->description }}</p>
                    </div>

                    <!-- Quantity and Add to Cart -->
                    <div class="product-actions">
                        <div class="quantity-section">
                            <!-- <label for="quantity">Quantity:</label> -->
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="product-quanity" value="1" min="1" max="10" readonly>
                                <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                        
                        <button class="add-to-cart add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection