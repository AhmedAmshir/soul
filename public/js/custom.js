// document.addEventListener('DOMContentLoaded', function () {
//     const cartDropdown = document.querySelector('.cart-dropdown');
//     const cartDropdownMenu = document.querySelector('.cart-dropdown-menu');

//     cartDropdown.addEventListener('mouseenter', function () {
//         cartDropdownMenu.style.display = 'block';
//     });

//     cartDropdown.addEventListener('mouseleave', function () {
//         cartDropdownMenu.style.display = 'none';
//     });
// });

$(document).ready(function () {

    $('.add-to-cart').on('click', function () {

        let productId = $('#product-id').val();
        let quantity = $('#product-quanity').val();
        let stock = $('#product-stock').val();

        $("#error-message").hide();
        $("#error-message").html('');
        if (parseInt(quantity) > parseInt(stock)) {
            $("#error-message").show();
            $("#error-message").html('Quantity exceeds available stock!');
        }
        
        $.ajax({
            url: addToCartUrl,
            method: 'POST',
            data: {
                variation_id: productId,
                quantity: quantity,
                stock: stock,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {

                    $('#cart-total-items').text(response.cart_total_items);
                    $('.cart-total-price').text('E£' + Math.round(response.cart_total_price));
                    $('#cartCount').text(response.cart_total_count);
                    $('.cart-dropdown-menu').html(response.cart_html);
                }
            },
            error: function (xhr) {
                $("#error-message").show();
                $("#error-message").html('An error occurred. Please try again.');
            }
        });
    });

    
    // Quantity controls
    window.increaseQuantity = function() {
        const quantityInput = document.getElementById('product-quanity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < 10) {
                quantityInput.value = currentValue + 1;
                updateTotalPrice();
            }
        }
    };
    
    window.decreaseQuantity = function() {
        const quantityInput = document.getElementById('product-quanity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotalPrice();
            }
        }
    };

    

    // $('.quantity-input').on('change', function (e) {

    //     e.preventDefault();
    //     e.stopPropagation();

    //     const cartItemId = $(this).data('cart-item-id');
    //     const quantity = parseInt($(this).val());

    //     $.ajax({
    //         url: incrementQuantityUrl,
    //         method: 'POST',
    //         data: {
    //             variation_id: cartItemId,
    //             quantity: quantity,
    //             _token: $('meta[name="csrf-token"]').attr('content'),
    //         },
    //         success: function (response) {
    //             if (response.success) {
    //                 $('#cart-total-items').text(response.cart_total_items);
    //                 $('.cart-total-price').text(response.cart_total_price);
    //                 $('#cart-item-quantity-' + cartItemId).text(quantity);
    //                 $('#cart-item-total-price-' + cartItemId).text('E£' + response.item_total_price);
    //             }
    //         },
    //         error: function (xhr) {
    //             alert('An error occurred. Please try again.');
    //         }
    //     });
    // });

    // $('.remove-item').on('click', function () {
    //     const cartItemId = $(this).data('cart-item-id');

    //     $.ajax({
    //         url: decrementQuantityUrl.replace(':cartItemId', cartItemId),
    //         method: 'POST',
    //         data: {
    //             variation_id: cartItemId,
    //             _token: $('meta[name="csrf-token"]').attr('content'),
    //         },
    //         success: function (response) {
    //             $('#cart-total-items').text(response.cart_total_items);
    //             $('.cart-total-price').text(response.cart_total_price);
    //             $('#cartCount').text(response.cart_total_count);
    //             $('.cart-item-' + cartItemId).fadeOut();
    //         },
    //         error: function (xhr) {
    //             alert('An error occurred. Please try again.');
    //         }
    //     });
    // });

});

function updateTotalPrice() {

    const quantityInput = document.getElementById('product-quanity');
    const totalPriceElement = document.getElementById('selectedScentPrice');
    const unitPriceInput = document.getElementById('product-price');
    
    if (quantityInput && totalPriceElement && unitPriceInput) {
        const quantity = parseInt(quantityInput.value);
        const unitPrice = parseFloat(unitPriceInput.value);
        const total = quantity * unitPrice;
        
        // Update the price display to show total without shipping
        totalPriceElement.textContent = `E£ ${Math.round(total)}`;
    }
}

function incrementCartQuantity(productId) {
    const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
    if (quantityInput) {
        const currentQuantity = parseInt(quantityInput.value);
        const newQuantity = Math.min(currentQuantity + 1, 10);
        updateCartQuantity(productId, newQuantity);
    }
}

function decrementCartQuantity(productId) {
    const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
    if (quantityInput) {
        const currentQuantity = parseInt(quantityInput.value);
        const newQuantity = Math.max(currentQuantity - 1, 1);
        updateCartQuantity(productId, newQuantity);
    }
}

function updateCartQuantity(productId, newQuantity) {
    if (typeof incrementQuantityUrl !== 'undefined') {
        // Use Laravel's update cart functionality
        fetch(incrementQuantityUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(newQuantity),
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateCartCount(data.cart_total_count);
                // Update the specific cart item quantity in the DOM
                const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
                if (quantityInput) {
                    quantityInput.value = data.item_quantity;
                }
                
                // Update individual item total
                const itemTotalElement = document.querySelector(`span[data-item-id="${productId}"]`);
                if (itemTotalElement && data.item_total_price) {
                    itemTotalElement.textContent = `E£ ${Math.round(data.item_total_price)}`;
                }
                
                // Update cart summary with server data
                const subtotalElement = document.getElementById('subtotal');
                const totalElement = document.getElementById('total');
                const shippingCost = 70;
                const totalWithShipping = data.cart_total_price + shippingCost;
                
                if (subtotalElement) subtotalElement.textContent = `E£ ${Math.round(data.cart_total_price)}`;
                if (totalElement) totalElement.textContent = `E£ ${Math.round(totalWithShipping)}`;
                
                // Update cart total price display
                const cartTotalPrice = document.querySelector('.cart-total-price');
                if (cartTotalPrice) {
                    cartTotalPrice.textContent = 'E£' + Math.round(data.cart_total_price);
                }
            } else {
                alert('Error updating cart: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating cart. Please try again.');
        });
    } else {
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

function removeFromCart(productId) {
    if (typeof decrementQuantityUrl !== 'undefined') {
        // Use Laravel's remove cart functionality
        fetch(decrementQuantityUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateCartCount(data.cart_total_items);
                
                // Update cart items count in header
                const cartItemsCount = document.getElementById('cart-items-count');
                if (cartItemsCount) {
                    const remainingItems = document.querySelectorAll('.cart-item-card').length - 1;
                    cartItemsCount.textContent = remainingItems.toString();
                }
                
                // Remove the cart item from the DOM
                const cartItem = document.querySelector(`input[data-product-id="${productId}"]`).closest('.cart-item-card');
                if (cartItem) {
                    cartItem.remove();
                }
                // Update cart summary with new totals
                updateCartSummary();
                // Update cart total price display
                const cartTotalPrice = document.querySelector('.cart-total-price');
                if (cartTotalPrice) {
                    cartTotalPrice.textContent = 'E£' + Math.round(data.cart_total_price);
                }
                // Check if cart is empty and show empty state
                const cartContent = document.getElementById('cartContent');
                const emptyCart = document.getElementById('emptyCart');
                const cartSummary = document.getElementById('cartSummary');
                
                if (cartContent && cartContent.querySelectorAll('.cart-item-card').length === 0) {
                    cartContent.style.display = 'none';
                    if (emptyCart) emptyCart.style.display = 'block';
                    if (cartSummary) cartSummary.style.display = 'none';
                }
            } else {
                alert('Error removing item: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing item. Please try again.');
        });
    } else {
        // Client-side cart removal
        const itemIndex = cart.findIndex(item => item.id === productId);
        if (itemIndex > -1) {
            cart.splice(itemIndex, 1);
            saveCart();
            updateCartCount();
            
            // Re-render cart page if we're on it
            if (window.location.pathname.includes('cart')) {
                renderCartItems();
            }
        }
    }
}

function clearCart() {
    if (confirm('Are you sure you want to clear all items from your cart?')) {
        if (typeof clearCartUrl !== 'undefined') {
            // Use server-side clear cart functionality
            fetch(clearCartUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update cart count
                    updateCartCount(0);
                    
                    // Update cart items count in header
                    const cartItemsCount = document.getElementById('cart-items-count');
                    if (cartItemsCount) {
                        cartItemsCount.textContent = '0';
                    }
                    
                    // Remove all cart items from DOM
                    const cartContent = document.getElementById('cartContent');
                    if (cartContent) {
                        cartContent.innerHTML = '';
                    }
                    
                    // Show empty cart state
                    const emptyCart = document.getElementById('emptyCart');
                    const cartSummary = document.getElementById('cartSummary');
                    
                    if (emptyCart) emptyCart.style.display = 'block';
                    if (cartSummary) cartSummary.style.display = 'none';
                    
                    // Update cart total price display
                    const cartTotalPrice = document.querySelector('.cart-total-price');
                    if (cartTotalPrice) {
                        cartTotalPrice.textContent = 'E£0.00';
                    }
                    
                    // Update summary totals
                    const subtotalElement = document.getElementById('subtotal');
                    const totalElement = document.getElementById('total');
                    if (subtotalElement) subtotalElement.textContent = 'E£0.00';
                    if (totalElement) totalElement.textContent = 'E£0.00';
                    
                    console.log('Cart cleared successfully');
                } else {
                    alert('Error clearing cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error clearing cart:', error);
                alert('Error clearing cart. Please try again.');
            });
        } else {
            // Fallback: remove items one by one (client-side only)
            const cartItems = document.querySelectorAll('.cart-item-card');
            const itemIds = Array.from(cartItems).map(item => {
                const input = item.querySelector('input[data-product-id]');
                return input ? input.getAttribute('data-product-id') : null;
            }).filter(id => id !== null);
            
            if (itemIds.length === 0) {
                return;
            }
            
            // Remove items sequentially
            let removedCount = 0;
            itemIds.forEach((itemId, index) => {
                setTimeout(() => {
                    removeFromCart(itemId);
                    removedCount++;
                    
                    // If this is the last item, reload the page to show empty state
                    if (removedCount === itemIds.length) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                }, index * 100);
            });
        }
    }
}

function updateCartCount(cartTotalItems = null) {
    const cartCounts = document.querySelectorAll('#cartCount');
    
    // If cartTotalItems is provided, use it; otherwise try to get from soul-script.js cart
    let totalItems = cartTotalItems;
    
    if (totalItems === null && typeof cart !== 'undefined') {
        totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    } else if (totalItems === null) {
        // Fallback: try to get from localStorage
        try {
            const storedCart = JSON.parse(localStorage.getItem('soul-cart')) || [];
            totalItems = storedCart.reduce((sum, item) => sum + item.quantity, 0);
        } catch (e) {
            totalItems = 0;
        }
    }
    
    cartCounts.forEach(element => {
        element.textContent = totalItems || 0;
        element.style.display = (totalItems || 0) > 0 ? 'flex' : 'none';
    });
}

function renderCartItems() {
    const cartContent = document.getElementById('cartContent');
    const emptyCart = document.getElementById('emptyCart');
    const cartSummary = document.getElementById('cartSummary');
    
    if (!cartContent) return;
    
    // Since cart is server-side, we don't need to render items here
    // The cart items are already rendered by the server in the Blade template
    // This function is mainly for client-side updates after AJAX calls
    
    // Check if cart is empty by looking at existing cart items
    const existingCartItems = cartContent.querySelectorAll('.cart-item');
    
    if (existingCartItems.length === 0) {
        cartContent.style.display = 'none';
        if (emptyCart) emptyCart.style.display = 'block';
        if (cartSummary) cartSummary.style.display = 'none';
        return;
    }
    
    cartContent.style.display = 'block';
    if (emptyCart) emptyCart.style.display = 'none';
    if (cartSummary) cartSummary.style.display = 'block';
    
    updateCartSummary();
}

function updateCartSummary() {
    
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    const shippingElement = document.getElementById('shipping');
    
    // Since cart data is server-side, we need to calculate from the existing DOM elements
    // or fetch updated data from the server
    const cartItems = document.querySelectorAll('.cart-item-card');
    let subtotal = 0;
    const shippingCost = 70; // Fixed shipping cost
    
    cartItems.forEach(item => {
        const priceElement = item.querySelector('.item-price');
        const quantityInput = item.querySelector('input[type="number"]');
        const itemTotalElement = item.querySelector('.item-total');
        
        if (priceElement && quantityInput) {
            const price = parseFloat(priceElement.textContent.replace('E£', ''));
            const quantity = parseInt(quantityInput.value) || 0;
            const itemTotal = price * quantity;
            subtotal += itemTotal;
            
            // Update individual item total
            if (itemTotalElement) {
                itemTotalElement.textContent = `E£ ${Math.round(itemTotal)}`;
            }
        }
    });
    
    const total = subtotal + shippingCost;
    const itemCount = cartItems.length;
    
    if (subtotalElement) {
        subtotalElement.textContent = `E£ ${Math.round(subtotal)}`;
    }
    if (shippingElement) shippingElement.textContent = `E£ ${shippingCost}`;
    if (totalElement) totalElement.textContent = `E£ ${Math.round(total)}`;
}

// Checkout Step Navigation
function goToStep(stepNumber) {
    console.log('Navigating to step:', stepNumber);
    
    // Hide all form steps
    const allSteps = document.querySelectorAll('.form-step');
    allSteps.forEach(step => {
        step.classList.remove('active');
    });
    
    // Show the target step
    const targetStep = document.querySelector(`[data-step="${stepNumber}"]`);
    if (targetStep) {
        targetStep.classList.add('active');
        console.log('Step', stepNumber, 'activated');
    } else {
        console.error('Step', stepNumber, 'not found');
    }
    
    // Update progress indicators
    const allProgressSteps = document.querySelectorAll('.progress-step');
    allProgressSteps.forEach((progressStep, index) => {
        if (index < stepNumber) {
            progressStep.classList.add('completed');
            progressStep.classList.remove('active');
        } else if (index === stepNumber - 1) {
            progressStep.classList.add('active');
            progressStep.classList.remove('completed');
        } else {
            progressStep.classList.remove('active', 'completed');
        }
    });
    
    // Update progress lines
    const progressLines = document.querySelectorAll('.progress-line');
    progressLines.forEach((line, index) => {
        if (index < stepNumber - 1) {
            line.classList.add('completed');
        } else {
            line.classList.remove('completed');
        }
    });
    
    // Scroll to top of form on mobile
    if (window.innerWidth <= 768) {
        const formCard = document.querySelector('.checkout-form-card');
        if (formCard) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

// Form validation for each step
function validateStep(stepNumber) {
    const step = document.querySelector(`[data-step="${stepNumber}"]`);
    if (!step) return false;
    
    const requiredFields = step.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    let firstErrorField = null;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            if (!firstErrorField) {
                firstErrorField = field;
            }
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    // Focus on first error field
    if (firstErrorField) {
        firstErrorField.focus();
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    return isValid;
}

// Initialize checkout page
function initCheckoutPage() {
    console.log('Initializing checkout page');
    
    // Add click event listeners to step navigation buttons
    const stepButtons = document.querySelectorAll('button[onclick^="goToStep"]');
    console.log('Found step buttons:', stepButtons.length);
    
    stepButtons.forEach(button => {
        // Remove onclick attribute and add event listener
        const onclickAttr = button.getAttribute('onclick');
        if (onclickAttr) {
            const stepNumber = parseInt(onclickAttr.match(/\d+/)[0]);
            button.removeAttribute('onclick');
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Button clicked for step:', stepNumber);
                
                const currentStep = document.querySelector('.form-step.active');
                const currentStepNumber = parseInt(currentStep.getAttribute('data-step'));
                
                // Validate current step before proceeding
                if (stepNumber > currentStepNumber && !validateStep(currentStepNumber)) {
                    console.log('Validation failed for step:', currentStepNumber);
                    return false;
                }
                
                goToStep(stepNumber);
            });
        }
    });
    
    // Add real-time validation
    const formInputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
    formInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error') && this.value.trim()) {
                this.classList.remove('error');
            }
        });
    });
    
    // Ensure first step is active on page load
    const firstStep = document.querySelector('[data-step="1"]');
    if (firstStep) {
        firstStep.classList.add('active');
    }
    
    console.log('Checkout page initialized successfully');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('checkout')) {
        initCheckoutPage();
    }
});