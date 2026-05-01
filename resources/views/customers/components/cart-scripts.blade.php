<script>
    // Cart Functionality
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const cartTotalElement = document.getElementById('cartTotal');

    function toggleCart(show) {
        if (!cartSidebar || !cartOverlay) return;
        if (show) {
            cartSidebar.classList.add('open');
            cartOverlay.classList.add('show');
            loadCart();
        } else {
            cartSidebar.classList.remove('open');
            cartOverlay.classList.remove('show');
        }
    }

    document.addEventListener('click', (e) => {
        if (e.target.closest('#cartToggle')) {
            e.preventDefault();
            toggleCart(true);
        }
        if (e.target.closest('#closeCart') || e.target === cartOverlay) {
            toggleCart(false);
        }

        // Add to Cart Logic
        if (e.target.closest('.btn-add')) {
            const btn = e.target.closest('.btn-add');
            const productId = btn.dataset.id;
            addToCart(productId);
        }

        // Update Cart Quantity
        if (e.target.closest('.btn-qty-adj')) {
            const btn = e.target.closest('.btn-qty-adj');
            const cartId = btn.dataset.cartId;
            const action = btn.dataset.action;
            updateCartQty(cartId, action);
        }

        // Remove from Cart
        if (e.target.closest('.btn-remove-item')) {
            const btn = e.target.closest('.btn-remove-item');
            const cartId = btn.dataset.cartId;
            deleteCartItem(cartId);
        }

        // Checkout Logic
        if (e.target.closest('.btn-checkout')) {
            checkout();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        loadCart();
    });

    async function checkout() {
        const cartItems = document.querySelectorAll('.cart-item');
        if (cartItems.length === 0) {
            if (typeof notyf !== 'undefined') notyf.error('Your cart is empty.');
            return;
        }

        Swal.fire({
            title: 'Place Order?',
            text: "Are you sure you want to proceed with the checkout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0284c7',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, checkout!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we place your order.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const note = document.getElementById('orderNote').value;

                    const response = await fetch("{{ route('customers.cart.checkout') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            note: note
                        })
                    });

                    const data = await response.json();
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            toggleCart(false);
                            const orderNote = document.getElementById('orderNote');
                            if (orderNote) orderNote.value = '';
                            loadCart(); // This will clear the UI
                            
                            // If we are on the orders page, we might want to refresh the list
                            if (window.location.pathname.includes('/orders')) {
                                window.location.reload();
                            }
                        });
                    } else {
                        if (typeof notyf !== 'undefined') notyf.error(data.message);
                    }
                } catch (error) {
                    console.error('Error during checkout:', error);
                    Swal.close();
                    if (typeof notyf !== 'undefined') notyf.error('An error occurred during checkout.');
                }
            }
        });
    }

    async function addToCart(productId) {
        try {
            const response = await fetch("{{ route('customers.cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            });

            const data = await response.json();
            if (data.success) {
                if (typeof notyf !== 'undefined') notyf.success(data.message);
                
                // Update Badge
                const badge = document.getElementById('cartCountBadge');
                if (badge && data.cart_count !== undefined) {
                    badge.textContent = data.cart_count;
                    badge.style.display = data.cart_count > 0 ? 'block' : 'none';
                }

                if (cartSidebar && cartSidebar.classList.contains('open')) {
                    loadCart();
                }
            } else {
                if (typeof notyf !== 'undefined') notyf.error(data.message);
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            if (typeof notyf !== 'undefined') notyf.error('An error occurred.');
        }
    }

    async function updateCartQty(cartId, action) {
        try {
            const response = await fetch("{{ route('customers.cart.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    action: action
                })
            });

            const data = await response.json();
            if (data.success) {
                loadCart();
            } else {
                if (typeof notyf !== 'undefined') notyf.error(data.message);
            }
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    }

    async function deleteCartItem(cartId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Remove this item from your cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, remove it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch("{{ route('customers.cart.delete') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cart_id: cartId
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        if (typeof notyf !== 'undefined') notyf.success(data.message);
                        loadCart();
                    } else {
                        if (typeof notyf !== 'undefined') notyf.error(data.message);
                    }
                } catch (error) {
                    console.error('Error deleting cart item:', error);
                }
            }
        });
    }

    async function loadCart() {
        if (!cartItemsContainer) return;
        
        cartItemsContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        try {
            const response = await fetch("{{ route('customers.cart.get') }}");
            const data = await response.json();

            if (data.success) {
                renderCart(data.cart);
            } else {
                cartItemsContainer.innerHTML =
                    `<p class="text-center text-muted">${data.message || 'Error loading cart'}</p>`;
            }
        } catch (error) {
            console.error('Error loading cart:', error);
            cartItemsContainer.innerHTML = '<p class="text-center text-muted">Error loading cart.</p>';
        }
    }

    function renderCart(cartItems) {
        // Update Navbar Badge
        const badge = document.getElementById('cartCountBadge');
        if (badge) {
            badge.textContent = cartItems.length;
            badge.style.display = cartItems.length > 0 ? 'block' : 'none';
        }

        if (!cartItemsContainer) return;

        if (cartItems.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-cart-x fs-1 text-muted opacity-50"></i>
                    <p class="mt-2 text-muted">Your cart is empty</p>
                </div>
            `;
            if (cartTotalElement) cartTotalElement.textContent = '₱0.00';
            return;
        }

        let html = '';
        let total = 0;

        cartItems.forEach(item => {
            const product = item.product;
            const totalLinePrice = item.line_total;
            total += totalLinePrice;

            const imgSrc = product.product_image ?
                `{{ asset('images/products/') }}/${product.product_image}` :
                '';

            let priceDetailHtml = '';
            if (item.wholesale_bundles > 0) {
                priceDetailHtml = `
                    <div class="text-end">
                        <span class="wholesale-badge">Wholesale Applied</span>
                        <div class="cart-item-price">₱${parseFloat(totalLinePrice).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        <small class="text-muted d-block" style="font-size: 0.7rem; line-height: 1.2;">
                            ${parseFloat(item.wholesale_bundles)}x (Whole)
                            ${item.regular_items > 0 ? `<br>${parseFloat(item.regular_items)}x (Retail)` : ''}
                        </small>
                    </div>
                `;
            } else {
                priceDetailHtml = `
                    <div class="cart-item-price">₱${parseFloat(totalLinePrice).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                `;
            }

            html += `
                <div class="cart-item">
                    ${product.product_image ? 
                        `<img src="${imgSrc}" class="cart-item-img" alt="${product.product_name}">` : 
                        `<div class="cart-item-img d-flex align-items-center justify-content-center bg-light fs-4">📦</div>`
                    }
                    <div class="cart-item-info">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="cart-item-name">${product.product_name}</div>
                            <button class="btn-remove-item" data-cart-id="${item.id}">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="cart-qty-wrap">
                                <button class="btn-qty-adj" data-cart-id="${item.id}" data-action="decrement">
                                    <i class="bi bi-dash-circle"></i>
                                </button>
                                <span class="cart-item-qty-val">${parseFloat(item.quantity)}</span>
                                <button class="btn-qty-adj" data-cart-id="${item.id}" data-action="increment">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                            </div>
                            ${priceDetailHtml}
                        </div>
                    </div>
                </div>
            `;
        });

        cartItemsContainer.innerHTML = html;
        if (cartTotalElement) {
            cartTotalElement.textContent =
                `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        }
    }
</script>
