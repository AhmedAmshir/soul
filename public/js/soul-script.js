// Cart functionality
let cart = JSON.parse(localStorage.getItem('soul-cart')) || [];

// Product data
const products = {
    'amber': {
        id: 'amber',
        name: 'Premium Essential Oil Diffuser - Amber',
        scent: 'Amber',
        description: 'Warm & Luxurious',
        price: 850,
        currency: 'EGP',
        image: '/img/products/amber-box-face.png'
    },
    'billa': {
        id: 'billa',
        name: 'Premium Essential Oil Diffuser - Billa',
        scent: 'Billa',
        description: 'Exotic & Mysterious',
        price: 850,
        currency: 'EGP',
        image: '/img/products/billa-box.png'
    },
    'gardenia': {
        id: 'gardenia',
        name: 'Premium Essential Oil Diffuser - Gardenia',
        scent: 'Gardenia',
        description: 'Floral & Elegant',
        price: 850,
        currency: 'EGP',
        image: '/img/products/gardenia-box-face.png'
    },
    'royal': {
        id: 'royal',
        name: 'Premium Essential Oil Diffuser - Royal',
        scent: 'Royal',
        description: 'Regal & Sophisticated',
        price: 850,
        currency: 'EGP',
        image: '/img/products/royal-box-face.png'
    },
    'vanilla': {
        id: 'vanilla',
        name: 'Premium Essential Oil Diffuser - Vanilla',
        scent: 'Vanilla',
        description: 'Warm & Comforting',
        price: 850,
        currency: 'EGP',
        image: '/img/products/vanilla-box-face.png'
    },
    'white-tea': {
        id: 'white-tea',
        name: 'Premium Essential Oil Diffuser - White-Tea',
        scent: 'White-Tea',
        description: 'Delicate & Refreshing',
        price: 850,
        currency: 'EGP',
        image: '/img/products/white-tea-box-face.png'
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    
    // Add quick add to cart functionality for homepage
    window.addToCart = function(scentId) {
        if (products[scentId]) {
            addToCart(products[scentId]);
        }
    };
    
    // Initialize product page functionality
    initProductPageNew();
    
    // Product page functionality
    if (window.location.pathname.includes('product') || window.location.pathname === '/product') {
        initProductPage();
    }
    
    // Cart page functionality
    if (window.location.pathname.includes('cart') || window.location.pathname === '/cart') {
        initCartPage();
    }
    
    // Checkout page functionality
    if (window.location.pathname.includes('checkout') || window.location.pathname === '/checkout') {
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
                // Submit form to Laravel backend
                const form = document.getElementById('checkoutForm');
                form.submit();
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
    
    // Use Laravel's add to cart functionality
    if (typeof addToCartUrl !== 'undefined' && typeof productId !== 'undefined') {
        fetch(addToCartUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                scent: scentId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`${product.scent} added to cart!`);
                updateCartCount();
                // Reload page to show updated cart count
                window.location.reload();
            } else {
                alert('Error adding to cart: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding to cart. Please try again.');
        });
    } else {
        // Fallback to localStorage cart
        const existingItemIndex = cart.findIndex(item => item.id === scentId);
        
        if (existingItemIndex > -1) {
            cart[existingItemIndex].quantity += quantity;
        } else {
            cart.push({
                ...product,
                quantity: quantity
            });
        }
        
        saveCart();
        updateCartCount();
        alert(`${product.scent} added to cart!`);
    }
}

// Remove from cart
function removeFromCart(productId) {
    if (typeof decrementQuantityUrl !== 'undefined') {
        // Use Laravel's remove from cart functionality
        fetch(decrementQuantityUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                // Reload page to show updated cart
                window.location.reload();
            } else {
                alert('Error removing item: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing item. Please try again.');
        });
    } else {
        // Fallback to localStorage cart
        cart = cart.filter(item => item.id !== productId);
        saveCart();
        updateCartCount();
        
        // Re-render cart page if we're on it
        if (window.location.pathname.includes('cart')) {
            renderCartItems();
        }
    }
}

// Update cart item quantity
function updateCartQuantity(productId, newQuantity) {
    if (typeof incrementQuantityUrl !== 'undefined') {
        // Use Laravel's update cart functionality
        const action = newQuantity > 0 ? 'increment' : 'decrement';
        const url = action === 'increment' ? incrementQuantityUrl : decrementQuantityUrl;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                // Reload page to show updated cart
                window.location.reload();
            } else {
                alert('Error updating cart: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating cart. Please try again.');
        });
    } else {
        // Fallback to localStorage cart
        const itemIndex = cart.findIndex(item => item.id === productId);
        
        if (itemIndex > -1) {
            if (newQuantity <= 0) {
                removeFromCart(productId);
            } else {
                cart[itemIndex].quantity = Math.min(newQuantity, 10);
                saveCart();
                updateCartCount();
                
                // Re-render cart page if we're on it
                if (window.location.pathname.includes('cart')) {
                    renderCartItems();
                }
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
                <p class="cart-item-price">$${Math.round(item.price)}</p>
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
            <div class="order-item-price">$${Math.round(item.price * item.quantity)}</div>
        </div>
    `).join('');
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    if (orderSubtotal) orderSubtotal.textContent = `$${Math.round(subtotal)}`;
    if (orderTotal) orderTotal.textContent = `$${Math.round(subtotal)}`;
}

// Update cart summary
function updateCartSummary() {
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    if (subtotalElement) subtotalElement.textContent = `$${Math.round(subtotal)}`;
    if (totalElement) totalElement.textContent = `$${Math.round(subtotal)}`;
}

// Update cart count in navigation
function updateCartCount() {
    alert('updateCartCount');
    const cartCounts = document.querySelectorAll('#cartCount');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    cartCounts.forEach(element => {
        element.textContent = totalItems;
        element.style.display = totalItems > 0 ? 'flex' : 'none';
    });
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('soul-cart', JSON.stringify(cart));
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

// New Product Page Functionality
function initProductPageNew() {
    // Thumbnail gallery functionality
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('mainProductImage');
    
    if (thumbnails.length > 0 && mainImage) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Update main image
                const imageSrc = this.dataset.image;
                mainImage.src = imageSrc;
            });
        });
    }
    
    // Selected scent display elements
    const selectedScentDisplay = document.getElementById('selectedScentDisplay');
    const selectedScentName = document.getElementById('selectedScentName');
    const selectedScentDescription = document.getElementById('selectedScentDescription');
    const selectedScentPrice = document.getElementById('selectedScentPrice');
    const totalPrice = document.getElementById('totalPrice');
    
    // Quantity controls
    window.increaseQuantity = function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < 10) {
                quantityInput.value = currentValue + 1;
                updateTotalPrice();
            }
        }
    };
    
    window.decreaseQuantity = function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotalPrice();
            }
        }
    };
    
    // Update total price function
    function updateTotalPrice() {
        const quantityInput = document.getElementById('quantity');
        const totalPriceElement = document.getElementById('totalPrice');
        const selectedQuantityElement = document.getElementById('selectedQuantity');
        
        if (quantityInput && totalPriceElement) {
            const quantity = parseInt(quantityInput.value);
            const price = 850; // EGP price
            const total = quantity * price;
            totalPriceElement.textContent = `${total} EGP`;
            
            // Update selected quantity badge
            if (selectedQuantityElement) {
                selectedQuantityElement.textContent = quantity;
            }
        }
    }
    
    // Add to cart functionality
    window.addToCart = function() {
        const quantityInput = document.getElementById('quantity');
        
        if (quantityInput) {
            const quantity = parseInt(quantityInput.value);
            const scentId = 'lavender'; // Default scent
            
            if (products[scentId]) {
                // Add multiple items to cart
                for (let i = 0; i < quantity; i++) {
                    addToCart(products[scentId]);
                }
                
                // Show success message
                showNotification(`${quantity} item(s) added to cart!`, 'success');
            }
        }
    };
    
    // Initialize total price
    updateTotalPrice();
}

// Notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#007bff'};
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        font-weight: 500;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
