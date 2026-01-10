<!-- <section class="bg-light">
    <div class="container py-5">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Featured Product</h1>
                <p>
                    Reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    Excepteur sint occaecat cupidatat non proident.
                </p>
            </div>
        </div>
        <div class="row">
            @foreach($products as $product)
                @foreach($product->variations as $variation)
                    <div class="col-12 col-md-4 mb-4">
                        <div class="card h-100">
                            <a href="{{ route('product', ['asin' => $variation->asin, 'slug' => Str::slug($variation->slug) ]) }}">
                                <img src="{{ asset('storage/products/' . $variation->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            </a>
                            <div class="card-body">
                                <ul class="list-unstyled d-flex justify-content-between">
                                    <li class="text-muted text-right">LE{{ $variation->price }}</li>
                                </ul>
                                <a href="{{ route('product', ['asin' => $variation->asin, 'slug' => Str::slug($variation->slug) ]) }}" class="h2 text-decoration-none text-dark">{{ $variation->smell }}</a>
                                <p class="card-text">
                                    {{ $variation->description }}
                                </p>
                                <p class="text-muted">{{ $variation->size_ml }}ml</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section> -->

<section class="products"  id="productsContainer">
        <div class="container">
            <h2 class="section-title">Our Signature Scents</h2>
            <div class="products-grid">

                @foreach($products as $product)
                    @foreach($product->variations as $variation)
                        <a href="{{ route('product', ['asin' => $variation->asin, 'slug' => Str::slug($variation->slug) ]) }}" class="product-card">
                            <div class="product-image">
                                <img src="{{ asset('storage/products/' . $variation->image) }}" alt="{{ $variation->name }}">
                            </div>
                            <div class="product-info">
                                <h3>{{ $variation->name }}</h3>
                                <p>{!! Str::limit($variation->description, 100, ' ...') !!}</p>
                                <div class="product-price">{{ number_format($variation->price, 0) }} LE</div>
                            </div>
                        </a>
                    @endforeach
                @endforeach
            </div>
            
            <!-- <div class="text-center" style="margin-top: var(--spacing-xl);">
                <a href="{{ route('product', ['slug' => 'premium-diffuser', 'asin' => 'B08N5WRWNW']) }}" class="btn btn-secondary btn-lg">View All Scents</a>
            </div> -->
        </div>
    </section>