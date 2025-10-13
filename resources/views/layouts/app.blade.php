<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Soul - Premium Essential Oil Diffusers')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Discover Soul\'s premium essential oil diffusers with natural healing scents. Transform your space into a sanctuary of wellness.')">

    <link rel="apple-touch-icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">

    <!-- New Soul Design CSS -->
    <link rel="stylesheet" href="{{ asset('css/soul-styles.css') }}">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <a href="{{ route('homepage') }}" class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="Soul" class="logo-img">
                </a>
                
                <div class="nav-links">
                    <a href="{{ route('homepage') }}" class="nav-link {{ request()->routeIs('homepage') ? 'active' : '' }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9,22 9,12 15,12 15,22"/>
                        </svg>
                        Home
                    </a>
                    <!-- <a href="{{ route('product', ['slug' => 'premium-diffuser', 'asin' => 'B08N5WRWNW']) }}" class="nav-link {{ request()->routeIs('product') ? 'active' : '' }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="m7.5 4.27 9 5.15"/>
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        </svg>
                        Product
                    </a> -->
                </div>

                <a href="{{ route('cart.view') }}" class="cart-btn {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span class="cart-count" id="cartCount">{{ $cartItemsCount ?? 0 }}</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 class="footer-logo">Soul</h3>
                    <p class="footer-description">Blending nature and elegance to create diffusers made from pure, natural ingredients. Each scent is thoughtfully crafted to inspire your senses, elevate your mood, and fill your space with lasting tranquility</p>
                </div>

                <!-- <div class="footer-section">
                    <h4 class="footer-title">Products</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('product', ['slug' => 'amber-musk-100ml', 'asin' => 'SO54582585']) }}?scent=amber">Amber</a></li>
                        <li><a href="{{ route('product', ['slug' => 'billa-100ml', 'asin' => 'SO85698745']) }}?scent=billa">Billa</a></li>
                        <li><a href="{{ route('product', ['slug' => 'gardenia-100ml', 'asin' => 'SO78588847']) }}?scent=gardenia">Gardenia</a></li>
                        <li><a href="{{ route('product', ['slug' => 'royal-100ml', 'asin' => 'SO65898955']) }}?scent=royal">Royal</a></li>
                        <li><a href="{{ route('product', ['slug' => 'vanilla-100ml', 'asin' => 'SO12023214']) }}?scent=vanilla">Vanilla</a></li>
                        <li><a href="{{ route('product', ['slug' => 'white-tea-100ml', 'asin' => 'SO12547896']) }}?scent=white-tea">White-Tea</a></li>
                    </ul>
                </div> -->

                <!-- <div class="footer-section">
                    <h4 class="footer-title">Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns & Exchanges</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Care Instructions</a></li>
                    </ul>
                </div> -->

                <div class="footer-section">
                <div class="footer-contact">
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span>123 Wellness Street, Aroma City 12345</span>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <span>hello@soularomatherapy.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-social">
                    <a href="https://facebook.com" target="_blank" class="social-link">
                        <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" class="social-link">
                        <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" class="social-link">
                        <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                        </svg>
                    </a>
                    <a href="https://linkedin.com" target="_blank" class="social-link">
                        <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                            <rect x="2" y="9" width="4" height="12"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                </div>
                <div class="footer-copyright">
                    <p>&copy; {{ date('Y') }} Soul. All rights reserved. | Designed with ❤️ for wellness</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="{{ asset('js/soul-script.js') }}"></script> -->
    <script src="{{ asset('js/custom.js') }}"></script>
</body>

</html>