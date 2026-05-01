<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Barlow+Condensed:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/customers-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/customers-home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
</head>

<body class="pos-page">

    <!-- ════════════════════════════
     TOP NAV
════════════════════════════ -->

    @include('customers.components.navbar')

    <!-- ════════════════════════════
     MAIN LAYOUT
════════════════════════════ -->
    <div class="pos-wrap">

        <!-- ── LEFT: PRODUCTS ── -->
        <div class="products-pane">
            <div class="search-wrap">
                <i class="bi bi-search si"></i>
                <input type="text" id="productSearch" placeholder="Search for products...">
            </div>

            <div class="grid-scroll">
                <div class="products-grid">
                    @foreach ($products as $product)
                        <div class="pcard {{ $product->quantity <= 0 ? 'out-of-stock' : '' }}">
                            <div class="pcard-img-wrap">
                                @if ($product->product_image)
                                    <img src="{{ asset('images/products/' . $product->product_image) }}"
                                        class="pcard-img" alt="{{ $product->product_name }}">
                                @else
                                    <div class="pcard-thumb">📦</div>
                                @endif

                                @if ($product->quantity <= 0)
                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; backdrop-filter: grayscale(1);">
                                    </div>
                                @endif
                            </div>
                            <div class="pcard-body">
                                <div class="pcard-name">{{ $product->product_name }}</div>
                                <div class="pcard-price">₱{{ number_format($product->selling_price, 2) }}</div>

                                @if ($product->quantity > 0)
                                    <button class="btn-add" data-id="{{ $product->id }}">
                                        <i class="bi bi-plus"></i> Add
                                    </button>
                                @else
                                    <button class="btn-add disabled" disabled
                                        style="background: #e2e8f0; color: #64748b; border-color: #cbd5e1; cursor: not-allowed;">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="noProductsFound" class="text-center py-5" style="display: none;">
                    <i class="bi bi-box2 fs-1 text-muted" style="opacity: 0.5;"></i>
                    <h5 class="mt-3 text-muted" style="font-weight: 600;">No products found</h5>
                    <p class="text-muted small">Try adjusting your search or category filter.</p>
                </div>
            </div>
        </div><!-- /products-pane -->

    </div><!-- /pos-wrap -->

    <div class="cart-overlay" id="cartOverlay"></div>

    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h5 class="cart-title">Your order</h5>
            <button class="btn-close-cart" id="closeCart">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="cart-body" id="cartItemsContainer">
            <!-- Cart items will be loaded here -->
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-note-wrap mb-3">
                <label for="orderNote" class="form-label small fw-bold text-muted mb-1">Add a note (optional)</label>
                <textarea class="form-control form-control-sm" id="orderNote" rows="2" placeholder="Special instructions..."></textarea>
            </div>
            <div class="cart-total-wrap">
                <span class="cart-total-label">Total</span>
                <span class="cart-total-value" id="cartTotal">₱0.00</span>
            </div>
            <button class="btn-checkout">Checkout</button>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Notyf Initialization
        const notyf = new Notyf({
            position: {
                x: 'center',
                y: 'top'
            },
            duration: 3000,
            types: [{
                type: 'success',
                background: '#10b981',
                icon: {
                    className: 'bi bi-check-circle fs-5 text-white',
                    tagName: 'i'
                }
            }]
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                notyf.success("{!! addslashes(session('success')) !!}");
            @endif

            @if (session('error'))
                notyf.error("{!! addslashes(session('error')) !!}");
            @endif
        });

        const THEME_KEY = 'naap-theme';
        const themeBtns = document.querySelectorAll('#posThemeToggle, #posThemeToggleMobile');
        const themeIcon = document.getElementById('posThemeToggleIcon');

        function applyTheme(theme) {
            document.body.classList.toggle('dark-mode', theme === 'dark');
            document.documentElement.setAttribute('data-bs-theme', theme);
            if (themeIcon) {
                themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        }

        const storedTheme = localStorage.getItem(THEME_KEY) || 'light';
        applyTheme(storedTheme);

        themeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const isDark = document.body.classList.contains('dark-mode');
                const nextTheme = isDark ? 'light' : 'dark';
                localStorage.setItem(THEME_KEY, nextTheme);
                applyTheme(nextTheme);
            });
        });

        // Product Search Functionality (Vanilla JS)
        document.getElementById('productSearch').addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.products-grid .pcard');
            const emptyState = document.getElementById('noProductsFound');
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.querySelector('.pcard-name').textContent.toLowerCase();
                if (name.includes(query)) {
                    card.style.display = "";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            });

            // Show/hide empty state
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? "block" : "none";
            }
        });

        // Cart Functionality
        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');
        const cartItemsContainer = document.getElementById('cartItemsContainer');
        const cartTotalElement = document.getElementById('cartTotal');

        function toggleCart(show) {
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

        async function checkout() {
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                notyf.error('Your cart is empty.');
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
                                document.getElementById('orderNote').value = '';
                                loadCart(); // This will clear the UI
                            });
                        } else {
                            notyf.error(data.message);
                        }
                    } catch (error) {
                        console.error('Error during checkout:', error);
                        Swal.close();
                        notyf.error('An error occurred during checkout.');
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
                    notyf.success(data.message);
                    
                    // Update Badge
                    const badge = document.getElementById('cartCountBadge');
                    if (badge && data.cart_count !== undefined) {
                        badge.textContent = data.cart_count;
                        badge.style.display = data.cart_count > 0 ? 'block' : 'none';
                    }

                    if (cartSidebar.classList.contains('open')) {
                        loadCart();
                    }
                } else {
                    notyf.error(data.message);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                notyf.error('An error occurred.');
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
                    notyf.error(data.message);
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
                            notyf.success(data.message);
                            loadCart();
                        } else {
                            notyf.error(data.message);
                        }
                    } catch (error) {
                        console.error('Error deleting cart item:', error);
                    }
                }
            });
        }

        async function loadCart() {
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

            if (cartItems.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x fs-1 text-muted opacity-50"></i>
                        <p class="mt-2 text-muted">Your cart is empty</p>
                    </div>
                `;
                cartTotalElement.textContent = '₱0.00';
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
            cartTotalElement.textContent =
                `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
