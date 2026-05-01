<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Barlow+Condensed:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/pos.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        .orders-container {
            flex: 1;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 30px 40px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .order-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            border: 1px solid #edf2f7;
            overflow: hidden;
        }

        .dark-mode .order-card {
            background: #111827;
            border-color: #1f2937;
        }

        .order-card-header {
            padding: 15px 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .order-number {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a202c;
        }

        .dark-mode .order-number {
            color: #f7fafc;
        }

        .order-date {
            font-size: 0.75rem;
            color: #718096;
            margin-top: 4px;
        }

        .customer-info {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1a202c;
            margin-top: 5px;
        }

        .dark-mode .customer-info {
            color: #cbd5e1;
        }

        .order-total {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0284c7;
        }

        .order-card-body {
            padding: 0 24px 15px 24px;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 15px;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-preparing {
            background: #e0f2fe;
            color: #0284c7;
        }

        .status-ready {
            background: #dcfce7;
            color: #16a34a;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-top: 1px solid #f1f5f9;
        }

        .dark-mode .order-item-row {
            border-top-color: #1f2937;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .item-qty-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-qty-subtle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: none;
            background: #e2e8f0;
            color: #4a5568;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .btn-qty-subtle:hover {
            background: #cbd5e1;
        }

        .dark-mode .btn-qty-subtle {
            background: #2d3748;
            color: #a0aec0;
        }

        .item-name {
            color: #4a5568;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .dark-mode .item-name {
            color: #a0aec0;
        }

        .item-price {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
        }

        .dark-mode .item-price {
            color: #e2e8f0;
        }

        .wholesale-details {
            font-size: 0.7rem;
            line-height: 1.2;
            display: block;
        }

        .order-note-section {
            font-size: 0.85rem;
            color: #4a5568;
            padding: 12px 0;
            border-top: 1px solid #f1f5f9;
        }

        .dark-mode .order-note-section {
            border-top-color: #1f2937;
            color: #a0aec0;
        }

        .order-card-footer {
            padding: 12px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafafa;
        }

        .dark-mode .order-card-footer {
            background: #111827;
            border-top-color: #1f2937;
        }

        .footer-link {
            text-decoration: none;
            color: #0284c7;
            font-weight: 700;
            font-size: 0.78rem;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .footer-link:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        .footer-link-add {
            color: #10b981;
        }

        .footer-link-cancel {
            color: #ef4444;
        }

        .btn-start-preparing {
            background: #0ea5e9;
            color: white;
            border: none;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.15);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-start-preparing:hover {
            background: #0284c7;
        }

        @media (max-width: 576px) {
            .order-card-footer {
                flex-direction: column;
                align-items: stretch !important;
                gap: 12px !important;
                padding: 15px !important;
            }

            .footer-actions-group {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 8px !important;
            }

            .footer-actions-group .btn-start-preparing {
                grid-column: span 2;
                order: -1;
                justify-content: center;
                padding: 10px !important;
            }

            .footer-actions-group .footer-link {
                justify-content: center;
                padding: 8px !important;
                background: rgba(0, 0, 0, 0.03);
                border-radius: 8px;
            }

            .dark-mode .footer-actions-group .footer-link {
                background: rgba(255, 255, 255, 0.05);
            }
        }

        /* Chat Interface Styling */
        .chat-section {
            display: none;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 15px 24px;
        }

        .chat-messages-container {
            max-height: 250px;
            overflow-y: auto;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding-right: 5px;
        }

        .chat-bubble {
            padding: 10px 15px;
            font-size: 0.85rem;
            position: relative;
            border-radius: 8px;
            max-width: 85%;
            width: fit-content;
        }

        .chat-bubble-customer {
            background: #e0f2fe;
            color: #1e293b;
            align-self: flex-start;
            text-align: left;
        }

        .chat-bubble-admin {
            background: #f1f5f9;
            color: #475569;
            align-self: flex-end;
            text-align: right;
        }

        .chat-time {
            font-size: 0.65rem;
            color: #94a3b8;
            margin-top: 5px;
            display: block;
        }

        .chat-input-group {
            display: flex;
            gap: 10px;
        }

        .chat-input {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.85rem;
            outline: none;
        }

        .btn-send-chat {
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0 16px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .wholesale-badge {
            background: #10b981;
            color: white;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 2px;
        }

        /* Product Modal Styling */
        .product-list-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .product-item:hover {
            background: #f8fafc;
        }

        .product-item-img {
            width: 45px;
            height: 45px;
            border-radius: 6px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .product-item-info {
            flex: 1;
            margin-left: 15px;
        }

        .product-item-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .product-item-price {
            font-size: 0.85rem;
            color: #64748b;
        }

        .dark-mode .chat-section {
            background: #111827;
            border-top-color: #1f2937;
        }

        .dark-mode .chat-bubble-customer {
            background: #1e3a8a;
            color: #f1f5f9;
        }

        .dark-mode .chat-bubble-admin {
            background: #1f2937;
            color: #cbd5e1;
        }

        .dark-mode .chat-input {
            background: #1f2937;
            border-color: #374151;
            color: #f1f5f9;
        }
    </style>
</head>

<body class="pos-page">

    <nav class="top-nav">
        <a href="{{ route('admin.dashboard.page') }}" class="nav-brand">
            <span class="back-icon"><i class="bi bi-arrow-left"></i></span>
            <i class="bi bi-grid-3x3-gap-fill"></i> POS
        </a>

        <button class="menu-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse"
            data-bs-target="#topNavMenu" aria-expanded="false" aria-controls="topNavMenu">
            <i class="bi bi-list"></i>
        </button>

        <div class="collapse d-lg-flex top-nav-menu flex-grow-1 ms-lg-3" id="topNavMenu">
            <div class="nav-menu-inner d-flex w-100 gap-2">
                <a href="{{ route('admin.dashboard.page') }}" class="nav-pill"><i class="bi bi-speedometer2"></i>
                    Dashboard</a>
                <a href="{{ route('admin.pos.page') }}" class="nav-pill"><i class="bi bi-calculator"></i> POS</a>
                <a href="#" class="nav-pill"><i class="bi bi-list-ul"></i> Orders</a>
                <a href="{{ route('admin.pending_orders.page') }}" class="nav-pill active">
                    <i class="bi bi-hourglass-split"></i> Pending
                    <span class="nav-badge" id="pending-count-badge">{{ count($orders) }}</span>
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

    <div class="pos-wrap">
        <div class="orders-container">
            @if ($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-receipt fs-1 text-muted opacity-50"></i>
                    <p class="mt-3 text-muted">No pending orders found.</p>
                </div>
            @else
                @foreach ($orders as $order)
                    @include('admin.pending_orders.order_card_partial', ['order' => $order])
                @endforeach
            @endif
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title w-100 text-center fw-bold">Add Item to Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-3 pb-4">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" id="modalProductSearch" class="form-control border-start-0"
                                placeholder="Search products...">
                        </div>
                    </div>
                    <div class="product-list-container">
                        @foreach ($products as $product)
                            <div class="product-item modal-product-btn" data-id="{{ $product->id }}"
                                data-name="{{ strtolower($product->product_name) }}">
                                <img src="{{ $product->product_image ? asset($product->product_image) : 'https://via.placeholder.com/50' }}"
                                    class="product-item-img" alt="">
                                <div class="product-item-info">
                                    <div class="product-item-name">{{ $product->product_name }}</div>
                                    <div class="product-item-price">₱{{ number_format($product->selling_price, 2) }} •
                                        Stock: {{ $product->quantity }}</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-plus-lg"></i> Add
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content"
                style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title w-100 text-center fw-bold" id="checkoutModalLabel">Checkout</h5>
                    <button type="button" class="btn-close d-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-3 pb-4">
                    <form id="pendingCheckoutForm">
                        <input type="hidden" id="checkoutOrderNumber">

                        <!-- Customer Details -->
                        <div class="mb-3 text-center">
                            <div class="fw-bold text-muted small mb-1" style="letter-spacing: 1px;">CUSTOMER NAME
                            </div>
                            <input type="text" id="checkoutCustomerName"
                                class="form-control text-center fw-bold bg-light border-0" readonly
                                style="font-size: 1.1rem; color: var(--text-primary);">
                        </div>

                        <!-- Discount -->
                        <div class="mb-3 text-center">
                            <button type="button" class="btn btn-link text-decoration-none p-0"
                                data-bs-toggle="collapse" data-bs-target="#discountCollapse">
                                <i class="bi bi-tag"></i> Add Discount
                            </button>
                            <div class="collapse" id="discountCollapse">
                                <input type="number" id="discountInput" class="form-control mt-2"
                                    placeholder="Discount Amount" min="0" step="0.01">
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="text-center mb-4">
                            <div style="color: #64748b; font-size: 0.9em;">Total Amount Due</div>
                            <div class="d-flex flex-column align-items-center justify-content-center mt-1">
                                <span id="originalTotalDisplay" class="text-muted text-decoration-line-through d-none"
                                    style="font-size: 1.1rem; margin-bottom: -5px;"></span>
                                <h2 class="fw-bold text-primary mb-0" id="checkoutTotalDisplay">₱0.00</h2>
                            </div>
                            <div id="discountAmountDisplay" class="text-danger small fw-bold d-none mt-1"></div>
                            <input type="hidden" id="finalTotalInput">
                        </div>

                        <!-- Payment Method Tabs -->
                        <ul class="nav nav-tabs w-100 mb-3" id="paymentMethodTabs" role="tablist">
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link active w-100 fw-bold" id="cash-tab" data-bs-toggle="tab"
                                    data-bs-target="#cash-pane" type="button" role="tab">Cash</button>
                            </li>
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link w-100 fw-bold" id="ecash-tab" data-bs-toggle="tab"
                                    data-bs-target="#ecash-pane" type="button" role="tab">E-Cash</button>
                            </li>
                        </ul>
                        <input type="hidden" id="paymentMethodInput" value="Cash">

                        <!-- Enter Payment Amount -->
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 0.85em; color: #64748b;">Enter
                                Payment Amount</label>
                            <input type="number" id="paymentAmountInput"
                                class="form-control text-center fs-4 fw-bold mb-2 shadow-sm" required min="0"
                                step="0.01" style="height: 60px; border-radius: 10px;">

                            <div class="d-flex justify-content-between gap-1 mt-2">
                                <button type="button"
                                    class="btn btn-light border flex-fill fw-bold checkout-quick-pay"
                                    data-val="50">₱50</button>
                                <button type="button"
                                    class="btn btn-light border flex-fill fw-bold checkout-quick-pay"
                                    data-val="100">₱100</button>
                                <button type="button"
                                    class="btn btn-light border flex-fill fw-bold checkout-quick-pay"
                                    data-val="200">₱200</button>
                                <button type="button"
                                    class="btn btn-light border flex-fill fw-bold checkout-quick-pay"
                                    data-val="500">₱500</button>
                                <button type="button"
                                    class="btn btn-light border flex-fill fw-bold checkout-quick-pay"
                                    data-val="1000">₱1,000</button>
                            </div>
                        </div>

                        <!-- Change Display -->
                        <div class="d-flex justify-content-between mb-4 px-2" style="font-size: 1.1em;">
                            <span class="text-secondary fw-bold">Change:</span>
                            <span class="fw-bold text-success" id="changeDisplay">₱0.00</span>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn w-100 py-3 fw-bold shadow-sm"
                            style="background-color: #10b981; color: #fff; border-radius: 10px; font-size: 1.1rem;">Confirm
                            Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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

        // AJAX Global Config
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Chat Polling
        let chatPollers = {};

        function startChatPolling(orderNumber) {
            // Strict validation: trim and check if truly empty or "undefined"
            const cleanedId = String(orderNumber).trim();
            if (!cleanedId || cleanedId === 'undefined' || cleanedId === 'null' || chatPollers[cleanedId]) {
                return;
            }

            chatPollers[cleanedId] = setInterval(async () => {
                try {
                    // Double check before fetching
                    if (!cleanedId) {
                        clearInterval(chatPollers[cleanedId]);
                        delete chatPollers[cleanedId];
                        return;
                    }

                    const response = await fetch(
                        `/admin/pending_orders/messages/${encodeURIComponent(cleanedId)}`);

                    if (!response.ok) {
                        if (response.status === 404) {
                            console.warn(`Order ${cleanedId} not found, stopping poll.`);
                            clearInterval(chatPollers[cleanedId]);
                            delete chatPollers[cleanedId];
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        throw new TypeError("Oops, we didn't get JSON!");
                    }

                    const data = await response.json();
                    if (data.success) {
                        const container = document.getElementById(`messages-container-${cleanedId}`);
                        const countSpan = document.getElementById(`chat-count-${cleanedId}`);

                        if (countSpan) countSpan.textContent = data.chats.length;

                        if (!container) return; // Guard for removed cards

                        const currentCount = container.querySelectorAll('.chat-bubble').length;
                        if (data.chats.length > currentCount) {
                            let html = '';
                            data.chats.forEach(chat => {
                                const bubbleClass = chat.is_customer ? 'chat-bubble-customer' :
                                    'chat-bubble-admin';
                                html += `<div class="chat-bubble ${bubbleClass}">
                                            <div class="chat-text">${chat.message}</div>
                                            <span class="chat-time">${chat.time}</span>
                                        </div>`;
                            });
                            container.innerHTML = html;
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                } catch (e) {
                    console.error("Polling error:", e);
                }
            }, 2000);
        }

        document.addEventListener('click', async (e) => {
            // Chat Toggle
            if (e.target.closest('.btn-toggle-chat')) {
                const btn = e.target.closest('.btn-toggle-chat');
                const orderNumber = btn.dataset.orderNumber;
                const chatSection = document.getElementById(`chat-${orderNumber}`);

                if (chatSection.style.display === 'block') {
                    chatSection.style.display = 'none';
                } else {
                    chatSection.style.display = 'block';
                    const container = chatSection.querySelector('.chat-messages-container');
                    container.scrollTop = container.scrollHeight;
                    startChatPolling(orderNumber);
                }
            }

            // Send Chat
            if (e.target.closest('.btn-send-chat')) {
                const btn = e.target.closest('.btn-send-chat');
                const orderNumber = btn.dataset.orderNumber;
                const input = document.getElementById(`input-${orderNumber}`);
                const message = input.value.trim();

                if (!message) return;

                const response = await fetch("{{ route('admin.pending_orders.send_chat') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        order_number: orderNumber,
                        message: message
                    })
                });

                const data = await response.json();
                if (data.success) {
                    input.value = '';
                    const container = document.getElementById(`messages-container-${orderNumber}`);
                    const bubble = document.createElement('div');
                    bubble.className = 'chat-bubble chat-bubble-admin';
                    bubble.innerHTML =
                        `<div class="chat-text">${data.chat.message}</div><span class="chat-time">${data.chat.time}</span>`;
                    container.appendChild(bubble);
                    container.scrollTop = container.scrollHeight;

                    const countSpan = document.getElementById(`chat-count-${orderNumber}`);
                    countSpan.textContent = parseInt(countSpan.textContent) + 1;
                }
            }

            // Quantity Adjustment
            if (e.target.closest('.btn-order-qty-adj')) {
                const btn = e.target.closest('.btn-order-qty-adj');
                const orderId = btn.dataset.orderId;
                const action = btn.dataset.action;

                const response = await fetch("{{ route('admin.pending_orders.update_qty') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        action: action
                    })
                });

                const data = await response.json();
                if (data.success) {
                    if (data.removed) {
                        document.getElementById(`item-row-${orderId}`).remove();
                    } else {
                        document.getElementById(`item-qty-${orderId}`).textContent = data.new_qty;
                        document.getElementById(`item-price-${orderId}`).textContent = '₱' + data
                            .new_line_total;

                        // Handle wholesale UI
                        const wsWrap = document.getElementById(`wholesale-wrap-${orderId}`);
                        const wsDetails = document.getElementById(`wholesale-details-${orderId}`);
                        if (data.is_wholesale) {
                            wsWrap.style.display = 'block';
                            wsDetails.style.display = 'block';
                            document.getElementById(`bundles-${orderId}`).textContent = data.wholesale_bundles;
                            const regWrap = document.getElementById(`regular-wrap-${orderId}`);
                            if (data.regular_items > 0) {
                                regWrap.style.display = 'inline';
                                document.getElementById(`regular-${orderId}`).textContent = data.regular_items;
                            } else {
                                regWrap.style.display = 'none';
                            }
                        } else {
                            wsWrap.style.display = 'none';
                            wsDetails.style.display = 'none';
                        }
                    }

                    // Find the order number from the parent card
                    const card = btn.closest('.order-card');
                    const orderNum = card.id.replace('order-card-', '');
                    document.getElementById(`order-total-${orderNum}`).textContent = '₱' + data.new_order_total;
                    notyf.success(data.message);
                } else {
                    notyf.error(data.message);
                }
            }

            // Start Preparing
            if (e.target.closest('.btn-action-start')) {
                const btn = e.target.closest('.btn-action-start');
                const orderNumber = btn.dataset.orderNumber;

                const response = await fetch("{{ route('admin.pending_orders.start_preparing') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        order_number: orderNumber
                    })
                });

                const data = await response.json();
                if (data.success) {
                    const badge = document.getElementById(`status-badge-${orderNumber}`);
                    badge.className = 'status-badge status-preparing';
                    badge.textContent = 'Preparing';

                    // Replace button with Mark as Ready
                    const container = btn.parentElement;
                    btn.remove();
                    const readyBtn = document.createElement('button');
                    readyBtn.className = 'btn-start-preparing btn-action-ready';
                    readyBtn.dataset.orderNumber = orderNumber;
                    readyBtn.style.background = '#10b981';
                    readyBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Mark as Ready';
                    container.appendChild(readyBtn);

                    notyf.success(data.message);
                }
            }

            // Mark as Ready
            if (e.target.closest('.btn-action-ready')) {
                const btn = e.target.closest('.btn-action-ready');
                const orderNumber = btn.dataset.orderNumber;

                const response = await fetch("{{ route('admin.pending_orders.mark_ready') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        order_number: orderNumber
                    })
                });

                const data = await response.json();
                if (data.success) {
                    const badge = document.getElementById(`status-badge-${orderNumber}`);
                    if (badge) {
                        badge.className = 'status-badge status-ready';
                        badge.textContent = 'Ready for pickup';
                    }

                    // Get container and card
                    const footerGroup = btn.parentElement;
                    const card = document.getElementById(`order-card-${orderNumber}`);

                    btn.remove();

                    // Add Checkout Button
                    const checkoutBtn = document.createElement('button');
                    checkoutBtn.className = 'btn-start-preparing btn-action-checkout';
                    checkoutBtn.dataset.orderNumber = orderNumber;
                    checkoutBtn.dataset.total = document.getElementById(`order-total-${orderNumber}`)
                        .textContent.replace('₱', '').replace(/,/g, '');
                    checkoutBtn.dataset.customerName = card.querySelector('.customer-info')
                        .textContent.split('(')[0].trim();
                    checkoutBtn.style.background = '#0284c7';
                    checkoutBtn.innerHTML = '<i class="bi bi-cart-check-fill"></i> Checkout';
                    footerGroup.appendChild(checkoutBtn);

                    notyf.success(data.message);
                }
            }

            // Open Checkout Modal
            if (e.target.closest('.btn-action-checkout')) {
                const btn = e.target.closest('.btn-action-checkout');
                const orderNumber = btn.dataset.orderNumber;
                
                // Get the live total from the UI element to ensure it's always accurate
                const totalElement = document.getElementById(`order-total-${orderNumber}`);
                const total = parseFloat(totalElement.textContent.replace('₱', '').replace(/,/g, ''));
                const customerName = btn.dataset.customerName;

                document.getElementById('checkoutOrderNumber').value = orderNumber;
                document.getElementById('checkoutCustomerName').value = customerName;
                document.getElementById('checkoutTotalDisplay').textContent = '₱' + total.toLocaleString(
                    undefined, {
                        minimumFractionDigits: 2
                    });
                document.getElementById('finalTotalInput').value = total;
                document.getElementById('paymentAmountInput').value = Math.ceil(total);
                document.getElementById('originalTotalDisplay').textContent = '₱' + total.toLocaleString(
                    undefined, {
                        minimumFractionDigits: 2
                    });
                document.getElementById('originalTotalDisplay').classList.add('d-none');
                document.getElementById('discountAmountDisplay').classList.add('d-none');
                document.getElementById('discountInput').value = '';

                baseCheckoutTotal = total;
                calculatePendingChange();

                const modalEl = document.getElementById('checkoutModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) modal = new bootstrap.Modal(modalEl);
                modal.show();
            }

            // Cancel Order
            if (e.target.closest('.btn-cancel-order')) {
                const btn = e.target.closest('.btn-cancel-order');
                const orderNumber = btn.dataset.orderNumber;

                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to cancel this order?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, cancel it!'
                });

                if (result.isConfirmed) {
                    const response = await fetch("{{ route('admin.pending_orders.cancel') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            order_number: orderNumber
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        document.getElementById(`order-card-${orderNumber}`).remove();
                        notyf.success(data.message);

                        // Update badge count
                        const badge = document.getElementById('pending-count-badge');
                        badge.textContent = parseInt(badge.textContent) - 1;
                    }
                }
            }

            // Open Product Modal
            if (e.target.closest('.btn-open-product-modal')) {
                const btn = e.target.closest('.btn-open-product-modal');
                currentOrderNumber = btn.dataset.orderNumber;
                const modalEl = document.getElementById('productModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) modal = new bootstrap.Modal(modalEl);
                modal.show();
            }

            // Add Product from Modal
            if (e.target.closest('.modal-product-btn')) {
                const btn = e.target.closest('.modal-product-btn');
                const productId = btn.dataset.id;

                const response = await fetch("{{ route('admin.pending_orders.add_item') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        order_number: currentOrderNumber,
                        product_id: productId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    // If new item, we might need to reload or manually append.
                    // For now, let's just reload to keep it simple, or manually append if I have the HTML.
                    // Manual append is better.
                    if (data.is_new) {
                        location.reload(); // Simple way for now
                    } else {
                        // Just update the existing row
                        document.getElementById(`item-qty-${data.item_id}`).textContent = data.new_qty;
                        document.getElementById(`item-price-${data.item_id}`).textContent = '₱' + data
                            .new_line_total;
                        document.getElementById(`order-total-${currentOrderNumber}`).textContent = '₱' + data
                            .new_order_total;
                        notyf.success(data.message);
                    }
                    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                } else {
                    notyf.error(data.message);
                }
            }
        });

        let currentOrderNumber = null;
        let baseCheckoutTotal = 0;

        function calculatePendingChange() {
            const total = parseFloat(document.getElementById('finalTotalInput').value) || 0;
            const paid = parseFloat(document.getElementById('paymentAmountInput').value) || 0;
            const change = paid - total;
            const changeDisplay = document.getElementById('changeDisplay');

            if (change >= 0) {
                changeDisplay.textContent = '₱' + change.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                });
                changeDisplay.classList.replace('text-danger', 'text-success');
            } else {
                changeDisplay.textContent = '-₱' + Math.abs(change).toLocaleString(undefined, {
                    minimumFractionDigits: 2
                });
                changeDisplay.classList.replace('text-success', 'text-danger');
            }
        }

        document.getElementById('paymentAmountInput')?.addEventListener('input', calculatePendingChange);

        document.getElementById('discountInput')?.addEventListener('input', function() {
            const discount = parseFloat(this.value) || 0;
            const newTotal = Math.max(0, baseCheckoutTotal - discount);
            document.getElementById('finalTotalInput').value = newTotal;
            document.getElementById('checkoutTotalDisplay').textContent = '₱' + newTotal.toLocaleString(undefined, {
                minimumFractionDigits: 2
            });

            if (discount > 0) {
                document.getElementById('originalTotalDisplay').classList.remove('d-none');
                const discountDisplay = document.getElementById('discountAmountDisplay');
                discountDisplay.textContent = '- ₱' + discount.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }) + ' Discount Applied';
                discountDisplay.classList.remove('d-none');
            } else {
                document.getElementById('originalTotalDisplay').classList.add('d-none');
                document.getElementById('discountAmountDisplay').classList.add('d-none');
            }

            document.getElementById('paymentAmountInput').value = Math.ceil(newTotal);
            calculatePendingChange();
        });

        document.querySelectorAll('.checkout-quick-pay').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('paymentAmountInput').value = this.dataset.val;
                calculatePendingChange();
            });
        });

        document.getElementById('cash-tab')?.addEventListener('click', () => document.getElementById('paymentMethodInput')
            .value = 'Cash');
        document.getElementById('ecash-tab')?.addEventListener('click', () => document.getElementById('paymentMethodInput')
            .value = 'E-Cash');

        document.getElementById('pendingCheckoutForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const orderNumber = document.getElementById('checkoutOrderNumber').value;
            const discount = document.getElementById('discountInput').value;
            const paymentAmount = document.getElementById('paymentAmountInput').value;
            const paymentMethod = document.getElementById('paymentMethodInput').value;
            const totalPrice = document.getElementById('finalTotalInput').value;

            if (parseFloat(paymentAmount) < parseFloat(totalPrice)) {
                notyf.error('Payment amount is less than total due!');
                return;
            }

            const response = await fetch("{{ route('admin.pending_orders.checkout') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_number: orderNumber,
                    discount_price: discount,
                    payment_amount: paymentAmount,
                    payment_method: paymentMethod,
                    total_price: totalPrice
                })
            });

            const data = await response.json();
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
                document.getElementById(`order-card-${orderNumber}`).remove();
                notyf.success(data.message);

                // Update badge count
                const badge = document.getElementById('pending-count-badge');
                badge.textContent = parseInt(badge.textContent) - 1;
            } else {
                notyf.error(data.message);
            }
        });

        // Modal Search
        document.getElementById('modalProductSearch')?.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.modal-product-btn').forEach(btn => {
                const name = btn.dataset.name;
                btn.style.display = name.includes(term) ? 'flex' : 'none';
            });
        });

        // Start polling for all pending orders on page load
        document.querySelectorAll('.btn-toggle-chat').forEach(btn => {
            const orderNumber = btn.dataset.orderNumber;
            if (orderNumber) startChatPolling(orderNumber);
        });

        // --- REAL-TIME ORDER POLLING ---
        async function checkNewOrders() {
            try {
                const response = await fetch("{{ route('admin.pending_orders.check') }}");
                if (!response.ok) return;

                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    return;
                }

                const data = await response.json();

                if (data.order_numbers) {
                    const currentOrderNumbers = Array.from(document.querySelectorAll('.order-card'))
                        .map(card => card.id.replace('order-card-', ''))
                        .filter(num => num && num !== '');

                    const newOrders = data.order_numbers.filter(num => num && !currentOrderNumbers.includes(num));
                    const removedOrders = currentOrderNumbers.filter(num => !data.order_numbers.includes(num));

                    // Add new orders
                    for (const orderNumber of newOrders) {
                        if (!orderNumber) continue;

                        const fetchResponse = await fetch(
                            `/admin/pending_orders/fetch-card/${encodeURIComponent(orderNumber)}`);
                        if (!fetchResponse.ok) continue;

                        const fetchContentType = fetchResponse.headers.get("content-type");
                        if (!fetchContentType || !fetchContentType.includes("application/json")) continue;

                        const fetchData = await fetchResponse.json();
                        if (fetchData.success) {
                            const container = document.querySelector('.orders-container');
                            const emptyMsg = container.querySelector('.text-center.py-5');
                            if (emptyMsg) emptyMsg.remove();

                            // Check if already added (to prevent race conditions)
                            if (document.getElementById(`order-card-${orderNumber}`)) continue;

                            // Insert at the top
                            container.insertAdjacentHTML('afterbegin', fetchData.html);

                            // Start chat polling for the new order
                            startChatPolling(orderNumber);

                            notyf.success(`New order received: ${orderNumber}`);

                            const badge = document.getElementById('pending-count-badge');
                            if (badge) badge.textContent = parseInt(badge.textContent) + 1;
                        }
                    }

                    // Remove missing orders
                    removedOrders.forEach(orderNumber => {
                        const card = document.getElementById(`order-card-${orderNumber}`);
                        if (card) {
                            card.remove();
                            // Update badge count
                            const badge = document.getElementById('pending-count-badge');
                            badge.textContent = Math.max(0, parseInt(badge.textContent) - 1);
                        }
                    });

                    // If all removed and none added, show empty message
                    if (data.order_numbers.length === 0 && !document.querySelector('.order-card') && !document
                        .querySelector('.text-center.py-5')) {
                        document.querySelector('.orders-container').innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-receipt fs-1 text-muted opacity-50"></i>
                                <p class="mt-3 text-muted">No pending orders found.</p>
                            </div>`;
                    }
                }
            } catch (error) {
                console.error("Error checking new orders:", error);
            }
        }

        // Check for new orders every 5 seconds
        setInterval(checkNewOrders, 3000);
    </script>
</body>

</html>
