@extends('layouts.app')

@section('title', 'Soul - Premium Essential Oil Diffusers')
@section('description', 'Discover Soul\'s premium essential oil diffusers with natural healing scents. Transform your space into a sanctuary of wellness.')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Inspire Your Senses</h1>
                <p class="hero-subtitle">SOUL is born from nature’s purest essence, each drop crafted to inspire your senses, nurture your soul, and connect you deeply with the earth</p>
                <a href="#productsContainer" class="btn btn-primary btn-lg">Shop Now</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Choose Soul?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-leaf w-8 h-8 text-primary" data-lov-id="src/pages/Landing.tsx:18:12" data-lov-name="Leaf" data-component-path="src/pages/Landing.tsx" data-component-line="18" data-component-file="Landing.tsx" data-component-name="Leaf" data-component-content="%7B%22className%22%3A%22w-8%20h-8%20text-primary%22%7D"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path></svg>
                    </div>
                    <h3>Natural Ingredients</h3>
                    <p>SOUL uses pure, natural ingredients carefully sourced to ensure safety, sustainability, and a clean fragrance experience that connects you to nature</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-8 h-8 text-primary" data-lov-id="src/pages/Landing.tsx:23:12" data-lov-name="Heart" data-component-path="src/pages/Landing.tsx" data-component-line="23" data-component-file="Landing.tsx" data-component-name="Heart" data-component-content="%7B%22className%22%3A%22w-8%20h-8%20text-primary%22%7D"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                    </div>
                    <h3>Long-Lasting Fragrance</h3>
                    <p>SOUL’s unique formula ensures a long-lasting aroma that fills your space for hours, leaving a memorable, soothing, and refreshing scent</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles w-8 h-8 text-primary" data-lov-id="src/pages/Landing.tsx:13:12" data-lov-name="Sparkles" data-component-path="src/pages/Landing.tsx" data-component-line="13" data-component-file="Landing.tsx" data-component-name="Sparkles" data-component-content="%7B%22className%22%3A%22w-8%20h-8%20text-primary%22%7D"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path><path d="M20 3v4"></path><path d="M22 5h-4"></path><path d="M4 17v2"></path><path d="M5 18H3"></path></svg>
                    </div>
                    <h3>Premium Quality</h3>
                    <p>Every SOUL product is crafted with premium materials and refined processes, delivering exceptional performance, elegant packaging, and consistent quality you can trust</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Signature Scents -->
    @include('partials.featured-products')

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Transform Your Space?</h2>
                <p>Experience the power of natural aromatherapy with our premium diffuser collection.</p>
                <a href="#productsContainer" class="btn btn-primary btn-lg">Explore Products</a>
            </div>
        </div>
    </section>
@endsection