@extends('orders.listing-layout')

@section('content')
    <!-- Orders Header -->
    <div class="orders-header">
        <div class="header-content">
            <div class="header-info">
                <h1 class="orders-title">Order Management</h1>
                <p class="orders-subtitle">Track and manage all customer orders</p>
            </div>
            <div class="header-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $orders->total() }}</span>
                    <span class="stat-label">Total Orders</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form action="{{ route('orders.index') }}" method="GET" class="filters-form">
            <div class="filter-group">
                <label for="status" class="filter-label">Filter by Status</label>
                <select name="status" id="status" class="filter-select">
                    <option value="">All Statuses</option>
                    @foreach(App\Enums\OrderStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Search
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4h16M4 8h16M4 12h16M4 16h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
        
        <!-- View Toggle -->
        <div class="view-toggle">
            <label class="view-toggle-label">View:</label>
            <div class="toggle-buttons">
                <button type="button" class="toggle-btn active" data-view="cards" title="Card View">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                        <rect x="14" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                        <rect x="3" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                        <rect x="14" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </button>
                <button type="button" class="toggle-btn" data-view="table" title="Table View">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 3h18v18H3zM3 9h18M3 15h18M9 3v18" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Grid (Card View) -->
    <div class="orders-grid" id="cards-view">
        @forelse($orders as $order)
            <div class="order-card">
                <div class="order-card-header">
                    <div class="order-info">
                        <h3 class="order-number">#{{ $order->order_number }}</h3>
                        <span class="order-date">{{ $order->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="order-status">
                        <span class="status-badge status-{{ strtolower($order->status) }}">
                            @switch($order->status)
                                @case(App\Enums\OrderStatus::PLACED->value)
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    @break
                                @case(App\Enums\OrderStatus::OUT_FOR_DELIVERY->value)
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    @break
                                @case(App\Enums\OrderStatus::DELIVERED->value)
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    @break
                            @endswitch
                        </span>
                    </div>
                </div>

                <div class="order-card-body">
                    <div class="customer-info">
                        <div class="info-item">
                            <span class="info-label">Customer</span>
                            <span class="info-value">
                                @if($order->user)
                                    {{ $order->user->name }}
                                @else
                                    Guest
                                @endif
                            </span>
                        </div>
                        @if($order->user)
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $order->user->email }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="shipping-info">
                        <div class="info-item">
                            <span class="info-label">Shipping Address</span>
                            <span class="info-value">
                                {{ $order->shippingAddress->full_name }}<br>
                                {{ $order->shippingAddress->address }}<br>
                                {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->governorate }}
                            </span>
                        </div>
                    </div>

                    <div class="order-totals">
                        <div class="total-item">
                            <span class="total-label">Total Amount</span>
                            <span class="total-value">E£{{ number_format($order->total_amount, 0) }}</span>
                        </div>
                        <div class="total-item">
                            <span class="total-label">Payment Method</span>
                            <span class="total-value">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                    </div>
                </div>

                <div class="order-card-footer">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="empty-title">No Orders Found</h3>
                <p class="empty-description">There are no orders matching your current filters.</p>
                <a href="{{ route('orders.index') }}" class="btn btn-primary">View All Orders</a>
            </div>
        @endforelse
    </div>

    <!-- Orders Table (Table View) -->
    <div class="orders-table-container" id="table-view" style="display: none;">
        <div class="table-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="order_number">
                            <span class="th-content">
                                Order Number
                                <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 9l4-4 4 4M16 15l-4 4-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </th>
                        <th class="sortable" data-sort="created_at">
                            <span class="th-content">
                                Date
                                <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 9l4-4 4 4M16 15l-4 4-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </th>
                        <th>Customer</th>
                        <th>Shipping Address</th>
                        <th class="sortable" data-sort="total_amount">
                            <span class="th-content">
                                Total Amount
                                <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 9l4-4 4 4M16 15l-4 4-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="order-row">
                            <td class="order-number-cell">
                                <div class="order-number-info">
                                    <span class="order-number">#{{ $order->order_number }}</span>
                                    <span class="payment-method">{{ strtoupper($order->payment_method) }}</span>
                                </div>
                            </td>
                            <td class="order-date-cell">
                                <div class="date-info">
                                    <span class="order-date">{{ $order->created_at->format('M j, Y') }}</span>
                                    <span class="order-time">{{ $order->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="customer-cell">
                                <div class="customer-info">
                                    <span class="customer-name">
                                        @if($order->user)
                                            {{ $order->user->name }}
                                        @else
                                            Guest
                                        @endif
                                    </span>
                                    @if($order->user)
                                    <span class="customer-email">{{ $order->user->email }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="address-cell">
                                <div class="address-info">
                                    <span class="address-name">{{ $order->shippingAddress->full_name }}</span>
                                    <span class="address-details">
                                        {{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->governorate }}
                                    </span>
                                </div>
                            </td>
                            <td class="total-cell">
                                <span class="total-amount">E£{{ number_format($order->total_amount, 0) }}</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-{{ strtolower($order->status) }}">
                                    @switch($order->status)
                                        @case(App\Enums\OrderStatus::PLACED->value)
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            @break
                                        @case(App\Enums\OrderStatus::OUT_FOR_DELIVERY->value)
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            @break
                                        @case(App\Enums\OrderStatus::DELIVERED->value)
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="actions-cell">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-table-state">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <h3 class="empty-title">No Orders Found</h3>
                                    <p class="empty-description">There are no orders matching your current filters.</p>
                                    <a href="{{ route('orders.index') }}" class="btn btn-primary">View All Orders</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inline JavaScript for immediate functionality -->
    <script>
    // Immediate script to ensure table view works
    document.addEventListener('DOMContentLoaded', function() {
        // View Toggle Functionality
        const toggleButtons = document.querySelectorAll('.toggle-btn');
        const cardsView = document.getElementById('cards-view');
        const tableView = document.getElementById('table-view');
        
        // Load saved view preference
        const savedView = localStorage.getItem('ordersView') || 'cards';
        switchView(savedView);
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const view = this.dataset.view;
                switchView(view);
                localStorage.setItem('ordersView', view);
            });
        });
        
        function switchView(view) {
            // Update active button
            toggleButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.view === view) {
                    btn.classList.add('active');
                }
            });
            
            // Show/hide views using CSS classes
            if (view === 'cards') {
                if (cardsView) {
                    cardsView.style.display = 'grid';
                    cardsView.classList.add('show');
                }
                if (tableView) {
                    tableView.style.display = 'none';
                    tableView.classList.remove('show');
                }
            } else {
                if (cardsView) {
                    cardsView.style.display = 'none';
                    cardsView.classList.remove('show');
                }
                if (tableView) {
                    tableView.style.display = 'block';
                    tableView.classList.add('show');
                }
            }
        }
    });
    </script>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-section">
        <!-- Results Info -->
        <div class="results-info">
            <div class="results-summary">
                <span class="results-text">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                </span>
                <span class="page-info">
                    Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}
                </span>
            </div>
        </div>

        <nav class="pagination-nav" aria-label="Page navigation">
            <ul class="pagination">
                <!-- First Page Link -->
                @if ($orders->currentPage() > 3)
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->url(1) }}" title="First Page">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 19l-7-7 7-7M20 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                @endif

                <!-- Previous Page Link -->
                @if ($orders->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link" title="Previous Page">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="page-text">Previous</span>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev" title="Previous Page">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="page-text">Previous</span>
                        </a>
                    </li>
                @endif

                <!-- Page Numbers -->
                @php
                    $start = max($orders->currentPage() - 2, 1);
                    $end = min($start + 4, $orders->lastPage());
                    $start = max($end - 4, 1);
                @endphp

                @if($start > 1)
                    <li class="page-item disabled">
                        <span class="page-link page-ellipsis">...</span>
                    </li>
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    <li class="page-item {{ $orders->currentPage() === $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $orders->url($page) }}">{{ $page }}</a>
                    </li>
                @endfor

                @if($end < $orders->lastPage())
                    <li class="page-item disabled">
                        <span class="page-link page-ellipsis">...</span>
                    </li>
                @endif

                <!-- Next Page Link -->
                @if ($orders->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next" title="Next Page">
                            <span class="page-text">Next</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" title="Next Page">
                            <span class="page-text">Next</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </li>
                @endif

                <!-- Last Page Link -->
                @if ($orders->currentPage() < $orders->lastPage() - 2)
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->url($orders->lastPage()) }}" title="Last Page">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 5l7 7-7 7M4 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Orders page JavaScript loaded');
    
    // View Toggle Functionality
    const toggleButtons = document.querySelectorAll('.toggle-btn');
    const cardsView = document.getElementById('cards-view');
    const tableView = document.getElementById('table-view');
    
    console.log('Toggle buttons found:', toggleButtons.length);
    console.log('Cards view found:', !!cardsView);
    console.log('Table view found:', !!tableView);
    
    // Load saved view preference
    const savedView = localStorage.getItem('ordersView') || 'cards';
    console.log('Saved view preference:', savedView);
    switchView(savedView);
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            console.log('Switching to view:', view);
            switchView(view);
            localStorage.setItem('ordersView', view);
        });
    });
    
    function switchView(view) {
        console.log('switchView called with:', view);
        
        // Update active button
        toggleButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.view === view) {
                btn.classList.add('active');
            }
        });
        
        // Show/hide views using CSS classes
        if (view === 'cards') {
            if (cardsView) {
                cardsView.style.display = 'grid';
                cardsView.classList.add('show');
            }
            if (tableView) {
                tableView.style.display = 'none';
                tableView.classList.remove('show');
            }
            console.log('Showing cards view');
        } else {
            if (cardsView) {
                cardsView.style.display = 'none';
                cardsView.classList.remove('show');
            }
            if (tableView) {
                tableView.style.display = 'block';
                tableView.classList.add('show');
            }
            console.log('Showing table view');
        }
    }
    
    // Table Sorting Functionality
    const sortableHeaders = document.querySelectorAll('.sortable');
    let currentSort = { column: null, direction: 'asc' };
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.sort;
            const direction = currentSort.column === column && currentSort.direction === 'asc' ? 'desc' : 'asc';
            
            // Update sort indicators
            sortableHeaders.forEach(h => {
                const icon = h.querySelector('.sort-icon');
                icon.style.transform = 'none';
                icon.style.opacity = '0.5';
            });
            
            const icon = this.querySelector('.sort-icon');
            icon.style.transform = direction === 'asc' ? 'rotate(180deg)' : 'rotate(0deg)';
            icon.style.opacity = '1';
            
            currentSort = { column, direction };
            
            // Here you would typically make an AJAX request to sort the data
            // For now, we'll just show a visual feedback
            console.log(`Sorting by ${column} in ${direction} direction`);
        });
    });
    
    // Table Row Hover Effects
    const tableRows = document.querySelectorAll('.order-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8fafc';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>
@endpush