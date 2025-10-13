
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - Soul</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/soul-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="orders-page">
    <!-- Header -->
    <header class="orders-page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-left">
                    <a href="{{ route('homepage') }}" class="back-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back to Store
                    </a>
                </div>
                <div class="header-center">
                    <h1 class="page-title">Order Management</h1>
                </div>
                <div class="header-right">
                    <!-- Future: Add admin controls or notifications -->
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="orders-main">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="orders-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; {{ date('Y') }} Soul. All rights reserved.</p>
                <div class="footer-links">
                    <a href="{{ route('homepage') }}">Home</a>
                    <a href="#">Support</a>
                    <a href="#">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>