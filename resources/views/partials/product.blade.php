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
                    <h1 class="product-title">{{ $variation->name }}</h1>

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

            <!-- FAQ Section -->
            <div class="faq-section">
                <h2 class="faq-title">FAQ</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Where are your products made?</h3>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>All our products are proudly made in Egypt with premium quality and 100% natural, safe ingredients. 🇪🇬</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>How long does the scent last?</h3>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>The scent usually lasts from 6 to 8 weeks, depending on the room size and airflow.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>What's the difference between a reed diffuser and a spray?</h3>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>The reed diffuser works automatically and spreads scent all day long, while the spray gives an instant burst of fragrance whenever you like.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Do you deliver?</h3>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, we deliver to all governorates in Egypt, and delivery usually takes 2 to 4 working days depending on your location.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>How do I use the reed diffuser correctly?</h3>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Simply open the cap, insert the sticks into the bottle, and flip them every 2–3 days to keep the scent fresh and strong.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>How can I make the scent last longer?</h3>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Keep your diffuser away from direct sunlight, air conditioners, and open windows. Use it in a cool, shaded spot and flip the reeds less often to make the fragrance last longer.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Are your scents allergy-safe?</h3>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, all SOUL fragrances are made with natural oils and are free from harsh chemicals 
                            or allergens.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFAQ(element) {
            const faqItem = element.parentElement;
            const faqAnswer = faqItem.querySelector('.faq-answer');
            const faqIcon = element.querySelector('.faq-icon');
            
            // Close all other FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('active');
                    const answer = item.querySelector('.faq-answer');
                    answer.style.display = 'none';
                    answer.style.maxHeight = null;
                    item.querySelector('.faq-icon').textContent = '+';
                }
            });
            
            // Toggle current FAQ item
            faqItem.classList.toggle('active');
            
            if (faqItem.classList.contains('active')) {
                faqAnswer.style.display = 'block';
                faqAnswer.style.maxHeight = faqAnswer.scrollHeight + 'px';
                faqIcon.textContent = '−';
            } else {
                faqAnswer.style.display = 'none';
                faqAnswer.style.maxHeight = null;
                faqIcon.textContent = '+';
            }
        }
    </script>
@endsection