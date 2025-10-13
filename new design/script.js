// Cart functionality
let cart = JSON.parse(localStorage.getItem('aromabliss-cart')) || [];

// Product data
const products = {
    'lavender': {
        id: 'lavender',
        name: 'Premium Essential Oil Diffuser - Lavender Dreams',
        scent: 'Lavender Dreams',
        description: 'Calming & Relaxing',
        price: 89.99,
        image: 'src/assets/diffuser-lavender.jpg'
    },
    'eucalyptus': {
        id: 'eucalyptus',
        name: 'Premium Essential Oil Diffuser - Eucalyptus Fresh',
        scent: 'Eucalyptus Fresh',
        description: 'Energizing & Purifying',
        price: 89.99,
        image: 'src/assets/diffuser-eucalyptus.jpg'
    },
    'vanilla': {
        id: 'vanilla',
        name: 'Premium Essential Oil Diffuser - Vanilla Comfort',
        scent: 'Vanilla Comfort',
        description: 'Warm & Comforting',
        price: 89.99,
        image: 'src/assets/diffuser-vanilla.jpg'
    },
    'citrus': {
        id: 'citrus',
        name: 'Premium Essential Oil Diffuser - Citrus Burst',
        scent: 'Citrus Burst',
        description: 'Uplifting & Refreshing',
        price: 89.99,
        image: 'src/assets/diffuser-citrus.jpg'
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    
    // Product page functionality
    if (window.location.pathname.includes('product.html') || window.location.pathname === '/product.html') {
        initProductPage();
    }
    
    // Cart page functionality
    if (window.location.pathname.includes('cart.html') || window.location.pathname === '/cart.html') {
        initCartPage();
    }
    
    // Checkout page functionality
    if (window.location.pathname.includes('checkout.html') || window.location.pathname === '/checkout.html') {
        initCheckoutPage();
    }
});

// Product page initialization
function initProductPage() {
    // Scent selection functionality
    const scentOptions = document.querySelectorAll('.scent-option');
    const selectedImage = document.getElementById('selectedImage');
    
    scentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            scentOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Update main product image
            const newImage = this.getAttribute('data-image');
            if (selectedImage && newImage) {
                selectedImage.src = newImage;
                selectedImage.alt = `${this.querySelector('h4').textContent} Diffuser`;
            }
        });
    });
}

// Cart page initialization
function initCartPage() {
    renderCartItems();
}

// Checkout page initialization
function initCheckoutPage() {
    renderOrderSummary();
    
    // Form submission
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple validation
            const formData = new FormData(this);
            const requiredFields = ['email', 'firstName', 'lastName', 'address', 'city', 'state', 'zipCode', 'cardNumber', 'expiryDate', 'cvv', 'cardName'];
            
            let isValid = true;
            requiredFields.forEach(field => {
                if (!formData.get(field)) {
                    isValid = false;
                }
            });
            
            if (isValid) {
                // Simulate order processing
                alert('Order placed successfully! Thank you for your purchase.');
                // Clear cart
                cart = [];
                saveCart();
                updateCartCount();
                // Redirect to home
                window.location.href = 'index.html';
            } else {
                alert('Please fill in all required fields.');
            }
        });
    }
}

// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        const currentValue = parseInt(quantityInput.value);
        quantityInput.value = Math.min(currentValue + 1, 10);
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        const currentValue = parseInt(quantityInput.value);
        quantityInput.value = Math.max(currentValue - 1, 1);
    }
}

// Add to cart functionality
function addToCart() {
    const activeScent = document.querySelector('.scent-option.active');
    const quantityInput = document.getElementById('quantity');
    
    if (!activeScent || !quantityInput) {
        alert('Please select a scent and quantity.');
        return;
    }
    
    const scentId = activeScent.getAttribute('data-scent');
    const quantity = parseInt(quantityInput.value);
    const product = products[scentId];
    
    if (!product) {
        alert('Product not found.');
        return;
    }
    
    // Check if item already exists in cart
    const existingItemIndex = cart.findIndex(item => item.id === scentId);
    
    if (existingItemIndex > -1) {
        // Update quantity
        cart[existingItemIndex].quantity += quantity;
    } else {
        // Add new item
        cart.push({
            ...product,
            quantity: quantity
        });
    }
    
    saveCart();
    updateCartCount();
    
    // Show confirmation
    alert(`${product.scent} added to cart!`);
}

// Remove from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    updateCartCount();
    
    // Re-render cart page if we're on it
    if (window.location.pathname.includes('cart.html')) {
        renderCartItems();
    }
}

// Update cart item quantity
function updateCartQuantity(productId, newQuantity) {
    const itemIndex = cart.findIndex(item => item.id === productId);
    
    if (itemIndex > -1) {
        if (newQuantity <= 0) {
            removeFromCart(productId);
        } else {
            cart[itemIndex].quantity = Math.min(newQuantity, 10);
            saveCart();
            updateCartCount();
            
            // Re-render cart page if we're on it
            if (window.location.pathname.includes('cart.html')) {
                renderCartItems();
            }
        }
    }
}

// Render cart items on cart page
function renderCartItems() {
    const cartContent = document.getElementById('cartContent');
    const emptyCart = document.getElementById('emptyCart');
    const cartSummary = document.getElementById('cartSummary');
    
    if (!cartContent) return;
    
    if (cart.length === 0) {
        cartContent.style.display = 'none';
        emptyCart.style.display = 'block';
        cartSummary.style.display = 'none';
        return;
    }
    
    cartContent.style.display = 'block';
    emptyCart.style.display = 'none';
    cartSummary.style.display = 'block';
    
    cartContent.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.scent}" />
            </div>
            <div class="cart-item-info">
                <h3>${item.scent}</h3>
                <p>${item.description}</p>
                <p class="cart-item-price">$${item.price.toFixed(2)}</p>
            </div>
            <div class="cart-item-actions">
                <div class="quantity-controls">
                    <button type="button" onclick="updateCartQuantity('${item.id}', ${item.quantity - 1})">-</button>
                    <input type="number" value="${item.quantity}" min="1" max="10" readonly>
                    <button type="button" onclick="updateCartQuantity('${item.id}', ${item.quantity + 1})">+</button>
                </div>
                <button class="remove-btn" onclick="removeFromCart('${item.id}')" aria-label="Remove item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');
    
    updateCartSummary();
}

// Render order summary on checkout page
function renderOrderSummary() {
    const orderItems = document.getElementById('orderItems');
    const orderSubtotal = document.getElementById('orderSubtotal');
    const orderTotal = document.getElementById('orderTotal');
    
    if (!orderItems) return;
    
    if (cart.length === 0) {
        orderItems.innerHTML = '<p>No items in cart</p>';
        return;
    }
    
    orderItems.innerHTML = cart.map(item => `
        <div class="order-item">
            <div class="order-item-image">
                <img src="${item.image}" alt="${item.scent}" />
            </div>
            <div class="order-item-info">
                <h4>${item.scent}</h4>
                <p>Qty: ${item.quantity}</p>
            </div>
            <div class="order-item-price">$${(item.price * item.quantity).toFixed(2)}</div>
        </div>
    `).join('');
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    if (orderSubtotal) orderSubtotal.textContent = `$${subtotal.toFixed(2)}`;
    if (orderTotal) orderTotal.textContent = `$${subtotal.toFixed(2)}`;
}

// Update cart summary
function updateCartSummary() {
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    if (subtotalElement) subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    if (totalElement) totalElement.textContent = `$${subtotal.toFixed(2)}`;
}

// Update cart count in navigation
function updateCartCount() {
    const cartCounts = document.querySelectorAll('#cartCount');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    cartCounts.forEach(element => {
        element.textContent = totalItems;
        element.style.display = totalItems > 0 ? 'flex' : 'none';
    });
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('aromabliss-cart', JSON.stringify(cart));
}

// Format card number input
document.addEventListener('input', function(e) {
    if (e.target.id === 'cardNumber') {
        let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        if (formattedValue !== e.target.value) {
            e.target.value = formattedValue;
        }
    }
    
    if (e.target.id === 'expiryDate') {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    }
    
    if (e.target.id === 'cvv') {
        e.target.value = e.target.value.replace(/[^0-9]/gi, '').substring(0, 4);
    }
});