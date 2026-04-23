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
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/pos.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        .qty-quick-btn {
            background-color: #e0f2fe;
            color: #0284c7;
            border: none;
            border-radius: 8px;
            width: 50px;
            height: 45px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .qty-quick-btn:hover {
            background-color: #bae6fd;
            color: #0369a1;
        }

        .dark-mode .modal-content {
            background-color: var(--pos-bg);
            border: 1px solid var(--pos-border);
        }

        .dark-mode #qtyModalLabel {
            color: var(--text-primary) !important;
        }

        .dark-mode .qty-quick-btn {
            background-color: rgba(2, 132, 199, 0.2);
            color: #38bdf8;
        }

        .dark-mode .qty-quick-btn:hover {
            background-color: rgba(2, 132, 199, 0.4);
            color: #7dd3fc;
        }

        .dark-mode #customQtyInput {
            background-color: var(--pos-bg-alt);
            color: var(--text-primary);
            border-color: var(--pos-border) !important;
        }
    </style>
</head>

<body class="pos-page">

    <!-- ════════════════════════════
     TOP NAV
════════════════════════════ -->
    <nav class="top-nav">
        <a href="{{ route('admin.dashboard.page') }}" class="nav-brand">
            <span class="back-icon"><i class="bi bi-arrow-left"></i></span>
            <i class="bi bi-grid-3x3-gap-fill"></i> POS
        </a>

        <!-- Hamburger button -->
        <button class="menu-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse"
            data-bs-target="#topNavMenu" aria-expanded="false" aria-controls="topNavMenu">
            <i class="bi bi-list"></i>
        </button>

        <!-- Collapsible Content -->
        <div class="collapse d-lg-flex top-nav-menu flex-grow-1 ms-lg-3" id="topNavMenu">
            <div class="nav-menu-inner d-flex w-100 gap-2">
                <a href="{{ route('admin.dashboard.page') }}" class="nav-pill"><i class="bi bi-speedometer2"></i>
                    Dashboard</a>
                <a href="#" class="nav-pill active"><i class="bi bi-calculator"></i> POS</a>
                <a href="#" class="nav-pill"><i class="bi bi-list-ul"></i> Orders</a>
                <a href="#" class="nav-pill">
                    <i class="bi bi-hourglass-split"></i> Pending
                    <span class="nav-badge">1</span>
                </a>

                <div class="ms-lg-auto d-flex flex-wrap align-items-center gap-2 right-actions">
                    <button class="theme-toggle" id="posThemeToggle" type="button" aria-label="Toggle dark mode"
                        style="background:transparent;border:none;color:white;font-size:1.2rem;cursor:pointer;">
                        <i class="bi bi-moon-stars" id="posThemeToggleIcon"></i>
                    </button>
                    <a href="#" class="nav-icon-pill">
                        <i class="bi bi-printer"></i>
                    </a>
                    <div class="admin-chip"><i class="bi bi-shield-check"></i>
                        {{ strtoupper(Auth::guard('admin')->user()->fullname) }}</div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-icon-pill border-0 bg-transparent" style="color: white;">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- ════════════════════════════
     MAIN LAYOUT
════════════════════════════ -->
    <div class="pos-wrap">

        <!-- ── LEFT: PRODUCTS ── -->
        <div class="products-pane">

            <!-- Search -->
            <div class="search-wrap">
                <i class="bi bi-search si"></i>
                <input type="text" placeholder="Search Product...">
            </div>

            <!-- Alpha Filter (CSS-only radio) -->
            <div class="alpha-row">
                <input type="radio" name="alpha" id="a-all" checked>
                <label class="alpha-lbl" for="a-all">All</label>

                @foreach (range('A', 'Z') as $letter)
                    <input type="radio" name="alpha" id="a-{{ $letter }}">
                    <label class="alpha-lbl" for="a-{{ $letter }}">{{ $letter }}</label>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="grid-scroll">
                <div class="products-grid">

                    @foreach ($products as $product)
                        <form action="{{ route('admin.pos.cart.add') }}" method="POST" class="pcard-form m-0 p-0"
                            data-name="{{ strtolower($product->product_name) }}"
                            data-letter="{{ strtolower(substr($product->product_name, 0, 1)) }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <div class="pcard" onclick="this.closest('form').submit()"
                                style="cursor: pointer; transition: transform 0.2s ease;">
                                <div class="pcard-img-wrap">
                                    <div class="pcard-thumb"
                                        style="padding: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                                        @if ($product->product_image)
                                            <img src="{{ asset('images/products/' . $product->product_image) }}"
                                                alt="{{ $product->product_name }}"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                        @else
                                            📦
                                        @endif
                                    </div>
                                    <span class="stock-zero">{{ $product->quantity }}</span>
                                </div>
                                <div class="pcard-body">
                                    <div class="pcard-name">{{ $product->product_name }}</div>
                                    <div class="pcard-price">₱{{ $product->selling_price }}</div>
                                    <button type="button" class="btn-add border-0 w-100"
                                        style="background: var(--pos-border);"><i class="bi bi-plus-lg"></i>
                                        Add</button>
                                </div>
                            </div>
                        </form>
                    @endforeach


                </div><!-- /products-grid -->
            </div><!-- /grid-scroll -->
        </div><!-- /products-pane -->


        <!-- ── RIGHT: ORDER PANEL ── -->
        <div class="order-pane offcanvas-lg offcanvas-end" tabindex="-1" id="orderPane"
            aria-labelledby="orderPaneLabel">

            <!-- Header -->
            <div class="order-head d-flex justify-content-between align-items-center">
                <h6 class="order-title mb-0" id="orderPaneLabel"><i class="bi bi-receipt me-1"></i> Current Order
                </h6>
                <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas"
                    data-bs-target="#orderPane" aria-label="Close"></button>
            </div>

            <!-- Order Items List -->
            <div class="order-list">
                @if ($cartItems->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-cart3"></i>
                        <p>No items added yet</p>
                        <span class="empty-hint">Click + Add on any product</span>
                    </div>
                @else
                    @foreach ($cartItems as $item)
                        <div class="order-item">
                            <div class="oi-emoji"
                                style="padding: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                @if ($item->product_image)
                                    <img src="{{ asset('images/products/' . $item->product_image) }}"
                                        alt="{{ $item->product_name }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                @else
                                    📦
                                @endif
                            </div>
                            <div class="oi-info">
                                <div class="oi-name">{{ $item->product_name }}</div>
                                <div class="oi-sub"
                                    style="font-size: 0.8em; line-height: 1.4; color: var(--text-secondary);">
                                    @if ($item->wholesale_bundles > 0)
                                        <span class="badge bg-success mb-1"
                                            style="font-size: 0.75em; padding: 2px 4px;">Wholesale Applied</span><br>
                                        {{ $item->wholesale_bundles }} × Whole
                                        (₱{{ number_format($item->whole_sale_price, 2) }})
                                        @if ($item->regular_items > 0)
                                            <br>{{ $item->regular_items + 0 }} × Retail
                                            (₱{{ number_format($item->selling_price, 2) }})
                                        @endif
                                    @else
                                        ₱{{ number_format($item->selling_price, 2) }} × {{ $item->quantity + 0 }}
                                    @endif
                                </div>
                            </div>
                            <div class="oi-controls">
                                <form action="{{ route('admin.pos.cart.update', $item->id) }}" method="POST"
                                    class="m-0 p-0" style="display: flex; align-items: center;">
                                    @csrf
                                    <input type="hidden" name="action" value="decrement">
                                    <button type="submit" class="qty-ctrl border-0 bg-transparent"
                                        style="padding: 0;"><i class="bi bi-dash"></i></button>
                                </form>
                                <form action="{{ route('admin.pos.cart.update', $item->id) }}" method="POST"
                                    class="m-0 p-0 mx-1">
                                    @csrf
                                    <input type="text" value="{{ $item->quantity + 0 }}" class="qty-val" readonly
                                        data-bs-toggle="modal" data-bs-target="#qtyModal"
                                        data-cart-id="{{ $item->id }}"
                                        data-current-qty="{{ $item->quantity + 0 }}"
                                        style="width: 45px; text-align: center; border: 1px solid var(--border-color, #ccc); border-radius: 4px; padding: 2px; font-weight: 600; background: transparent; color: inherit; cursor: pointer;">
                                </form>
                                <form action="{{ route('admin.pos.cart.update', $item->id) }}" method="POST"
                                    class="m-0 p-0" style="display: flex; align-items: center;">
                                    @csrf
                                    <input type="hidden" name="action" value="increment">
                                    <button type="submit" class="qty-ctrl border-0 bg-transparent"
                                        style="padding: 0;"><i class="bi bi-plus"></i></button>
                                </form>
                            </div>
                            <div class="oi-price">₱{{ number_format($item->line_total, 2) }}
                            </div>
                            <form action="{{ route('admin.pos.cart.delete', $item->id) }}" method="POST"
                                class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="oi-del"
                                    style="background-color: #d93025; color: white; border: none;"><i
                                        class="bi bi-x-lg"></i></button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Total -->
            <div class="order-total-bar">
                <div class="total-row">
                    <span class="total-lbl">Total Amount</span>
                    <span class="total-val">₱{{ number_format($subtotal, 2) }}</span>
                    <span class="total-lbl"
                        style="font-size: 0.85em; margin-top: 5px; width: 100%; display: flex; justify-content: flex-end; align-items: center; gap: 6px;">
                        Est. Profit
                        <i class="bi bi-eye-slash-fill" id="toggleProfitBtn"
                            style="cursor: pointer; color: var(--text-secondary);"
                            title="Toggle Profit Visibility"></i>
                        <span id="profitValue"
                            class="{{ $profit < 0 ? 'text-danger' : 'text-success' }} fw-bold d-none">
                            {{ $profit < 0 ? '-' : '' }}₱{{ number_format(abs($profit), 2) }}
                        </span>
                        <span id="profitHidden" class="fw-bold" style="color: var(--text-secondary);">.......</span>
                    </span>
                </div>
                <hr class="divider-dots">
            </div>

            <!-- Actions -->
            <div class="order-actions">
                <button class="btn-checkout" data-bs-toggle="modal" data-bs-target="#checkoutModal"
                    {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-bag-check"></i> Checkout
                </button>
                <div class="bottom-actions">
                    <button class="bot-btn cam"><i class="bi bi-camera"></i> Camera</button>
                    <button class="bot-btn cust" data-bs-toggle="modal" data-bs-target="#customItemModal"><i
                            class="bi bi-plus-circle"></i> Custom</button>
                    @if ($cartItems->isEmpty())
                        <button class="bot-btn sav w-100" data-bs-toggle="modal"
                            data-bs-target="#savedOrdersModal"><i class="bi bi-folder2-open"></i> Saved</button>
                    @else
                        <button class="bot-btn w-100" type="button" id="saveOrderBtn"><i class="bi bi-save"></i>
                            Save</button>
                    @endif
                </div>
            </div>

        </div><!-- /order-pane -->

    </div><!-- /pos-wrap -->

    <!-- Mobile View Cart FAB Button -->
    <button class="cart-fab d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#orderPane"
        aria-controls="orderPane">
        <i class="bi bi-cart3"></i>
        <span class="cart-fab-badge">{{ $cartItems->count() }}</span>
    </button>



    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('admin.pos.modals.saved_item')
    @include('admin.pos.modals.quantity')
    @include('admin.pos.modals.checkout')
    @include('admin.pos.modals.custom_item')

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

            // Product Filtering Logic
            const searchInput = document.querySelector('.search-wrap input');
            const alphaRadios = document.querySelectorAll('.alpha-row input[type="radio"]');
            const productCards = document.querySelectorAll('.pcard-form');

            function filterProducts() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                const activeAlpha = document.querySelector('.alpha-row input[type="radio"]:checked');
                const alphaVal = activeAlpha ? activeAlpha.id.replace('a-', '').toLowerCase() : 'all';

                productCards.forEach(card => {
                    const name = card.dataset.name || '';
                    const letter = card.dataset.letter || '';

                    const matchesSearch = name.includes(searchTerm);
                    const matchesAlpha = alphaVal === 'all' || letter === alphaVal;

                    if (matchesSearch && matchesAlpha) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterProducts);
            }
            alphaRadios.forEach(radio => radio.addEventListener('change', filterProducts));

            // Quantity Modal Logic
            const qtyModal = document.getElementById('qtyModal');
            if (qtyModal) {
                qtyModal.addEventListener('show.bs.modal', function(event) {
                    const input = event.relatedTarget;
                    const cartId = input.getAttribute('data-cart-id');
                    const currentQty = input.getAttribute('data-current-qty');

                    const form = document.getElementById('qtySetForm');
                    form.action = `/admin/pos/cart/${cartId}/update`;

                    document.getElementById('customQtyInput').value = currentQty;
                });

                document.querySelectorAll('.qty-quick-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.getElementById('customQtyInput').value = this.getAttribute(
                            'data-val');
                    });
                });
            }

            // Checkout Modal Logic
            const paymentAmountInput = document.getElementById('paymentAmountInput');
            const changeDisplay = document.getElementById('changeDisplay');
            const checkoutTotalInput = document.getElementById('checkoutTotalInput');
            const checkoutTotalDisplay = document.getElementById('checkoutTotalDisplay');
            const discountInput = document.querySelector('input[name="discount_price"]');
            const baseTotal = parseFloat("{{ $subtotal }}") || 0;

            function calculateChange() {
                const total = parseFloat(checkoutTotalInput.value) || 0;
                const paid = parseFloat(paymentAmountInput.value) || 0;
                const change = paid - total;
                if (change >= 0) {
                    changeDisplay.textContent = '₱' + change.toFixed(2);
                    changeDisplay.classList.replace('text-danger', 'text-success');
                } else {
                    changeDisplay.textContent = '-₱' + Math.abs(change).toFixed(2);
                    changeDisplay.classList.replace('text-success', 'text-danger');
                }
            }

            if (paymentAmountInput) {
                paymentAmountInput.addEventListener('input', calculateChange);
                paymentAmountInput.value = Math.ceil(baseTotal);
                calculateChange();
            }

            if (discountInput) {
                const originalTotalDisplay = document.getElementById('originalTotalDisplay');
                const discountAmountDisplay = document.getElementById('discountAmountDisplay');

                discountInput.addEventListener('input', function() {
                    const discount = parseFloat(this.value) || 0;
                    const newTotal = Math.max(0, baseTotal - discount);
                    checkoutTotalInput.value = newTotal;
                    checkoutTotalDisplay.textContent = '₱' + newTotal.toFixed(2);

                    if (discount > 0) {
                        if (originalTotalDisplay) originalTotalDisplay.classList.remove('d-none');
                        if (discountAmountDisplay) {
                            discountAmountDisplay.textContent = '- ₱' + discount.toFixed(2) +
                                ' Discount Applied';
                            discountAmountDisplay.classList.remove('d-none');
                        }
                    } else {
                        if (originalTotalDisplay) originalTotalDisplay.classList.add('d-none');
                        if (discountAmountDisplay) discountAmountDisplay.classList.add('d-none');
                    }

                    if (paymentAmountInput) {
                        paymentAmountInput.value = Math.ceil(newTotal);
                    }
                    calculateChange();
                });
            }

            document.querySelectorAll('.quick-pay-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    paymentAmountInput.value = this.getAttribute('data-val');
                    calculateChange();
                });
            });

            const cashTab = document.getElementById('cash-tab');
            const ecashTab = document.getElementById('ecash-tab');
            const paymentMethodInput = document.getElementById('paymentMethodInput');

            if (cashTab && ecashTab) {
                cashTab.addEventListener('click', () => paymentMethodInput.value = 'Cash');
                ecashTab.addEventListener('click', () => paymentMethodInput.value = 'E-Cash');
            }

            // Customer Validation Logic
            const customerNameInput = document.querySelector('input[name="customer_name"]');
            const addCustomerBtn = document.querySelector('[data-bs-target="#customerDetailsCollapse"]');
            const customerDetailsCollapse = document.getElementById('customerDetailsCollapse');

            if (customerNameInput && addCustomerBtn) {
                customerNameInput.addEventListener('invalid', function(e) {
                    addCustomerBtn.classList.add('text-danger');

                    if (!customerDetailsCollapse.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(customerDetailsCollapse, {
                            toggle: false
                        });
                        bsCollapse.show();
                    }
                });

                customerNameInput.addEventListener('input', function() {
                    if (this.value.trim()) {
                        addCustomerBtn.classList.remove('text-danger');
                    }
                });
            }

            // Profit visibility toggle
            const toggleProfitBtn = document.getElementById('toggleProfitBtn');
            const profitValue = document.getElementById('profitValue');
            const profitHidden = document.getElementById('profitHidden');

            if (toggleProfitBtn) {
                toggleProfitBtn.addEventListener('click', function() {
                    if (profitValue.classList.contains('d-none')) {
                        profitValue.classList.remove('d-none');
                        profitHidden.classList.add('d-none');
                        toggleProfitBtn.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                    } else {
                        profitValue.classList.add('d-none');
                        profitHidden.classList.remove('d-none');
                        toggleProfitBtn.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                    }
                });
            }

            // Save Order logic
            const saveOrderBtn = document.getElementById('saveOrderBtn');
            if (saveOrderBtn) {
                saveOrderBtn.addEventListener('click', function() {
                    const hasItems = {{ $cartItems->isEmpty() ? 'false' : 'true' }};
                    if (!hasItems) {
                        notyf.error('Cart is empty!');
                        return;
                    }

                    Swal.fire({
                        title: 'Save Order?',
                        text: "Are you sure you want to save this order?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('saveOrderForm').submit();
                        }
                    });
                });
            }
        });

        const THEME_KEY = 'naap-theme';
        const themeBtn = document.getElementById('posThemeToggle');
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

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                const isDark = document.body.classList.contains('dark-mode');
                const nextTheme = isDark ? 'light' : 'dark';
                localStorage.setItem(THEME_KEY, nextTheme);
                applyTheme(nextTheme);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
