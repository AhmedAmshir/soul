<nav class="navbar navbar-expand-lg navbar-light shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Left Section (Search or Menu Button on Mobile) -->
        <div class="d-flex align-items-center">
            <button class="navbar-toggler border-0 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- Center Logo -->
        <a class="navbar-brand text-success logo h1 mx-auto" href="{{ route('homepage') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Soul" style="width: 70px;">
        </a>

        <!-- Right Section (Cart) -->
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <a class="nav-icon position-relative text-decoration-none" href="#" role="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-soul text-dark" id="cart-items-count">
                        {{ $cartItemsCount }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end cart-dropdown-menu" aria-labelledby="cartDropdown">
                    @include('partials.cart-items')
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="get" class="modal-content modal-body border-0 p-0">
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                <button type="submit" class="input-group-text bg-success text-soul">
                    <i class="fa fa-fw fa-search text-white"></i>
                </button>
            </div>
        </form>
    </div>
</div>

