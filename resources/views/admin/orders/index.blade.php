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

        .cancelled-order {
            opacity: 0.6;
            filter: grayscale(0.6);
            cursor: not-allowed;
        }

        .cancelled-order .item-name,
        .cancelled-order .order-number,
        .cancelled-order .customer-info {
            text-decoration: line-through;
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

        .order-card-body {
            padding: 0 24px 15px 24px;
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

        .order-card-footer {
            padding: 10px 24px;
            background: #fff;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark-mode .order-card-footer {
            background: #111827;
            border-top-color: #1f2937;
        }

        .payment-summary-row {
            padding: 10px 24px;
            background: #fafafa;
            margin: 0 -24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
        }

        .payment-summary-row.main {
            border-top: 2px solid #f1f5f9;
        }

        .dark-mode .payment-summary-row {
            background: #1f2937;
            border-top-color: #374151;
        }

        .order-note-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f0;
            font-size: 0.85rem;
            color: #718096;
        }

        .dark-mode .order-note-section {
            border-top-color: #374151;
            color: #94a3b8;
        }

        /* Receipt Modal Styling */
        .receipt-modal-content {
            font-family: 'Inter', sans-serif;
            padding: 20px;
            color: #1a202c;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            margin-bottom: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .receipt-header p {
            font-size: 0.85rem;
            color: #718096;
            margin: 0;
        }

        .receipt-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: #4a5568;
            margin-bottom: 15px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 10px;
        }

        .receipt-items {
            margin-bottom: 15px;
        }

        .receipt-item-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .receipt-item-name {
            font-weight: 700;
            color: #1a202c;
        }

        .receipt-item-qty {
            font-size: 0.75rem;
            color: #718096;
            display: block;
        }

        .receipt-summary {
            border-top: 1px dashed #e2e8f0;
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .summary-row.total {
            font-weight: 800;
            font-size: 1.1rem;
            margin-top: 5px;
            padding-top: 5px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 0.75rem;
            color: #a0aec0;
            font-style: italic;
        }

        .dark-mode .receipt-modal-content {
            color: #f7fafc;
        }

        .dark-mode .receipt-item-name {
            color: #f7fafc;
        }

        .dark-mode .receipt-header p,
        .dark-mode .receipt-info,
        .dark-mode .receipt-item-qty {
            color: #a0aec0;
        }

        .dark-mode .receipt-info,
        .dark-mode .receipt-summary {
            border-top-color: #374151;
            border-bottom-color: #374151;
        }

        /* Return Modal Styling */
        .return-modal-content {
            padding: 20px;
        }

        .return-info-alert {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 12px 16px;
            color: #0369a1;
            font-size: 0.85rem;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .return-item-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }

        .return-item-card:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .return-item-card.selected {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .return-item-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .return-item-details {
            flex: 1;
        }

        .return-item-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .return-item-meta {
            font-size: 0.75rem;
            color: #64748b;
        }

        .return-checkbox-wrapper {
            width: 24px;
            height: 24px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .selected .return-checkbox-wrapper {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .return-checkbox-wrapper i {
            color: white;
            font-size: 0.8rem;
            display: none;
        }

        .selected .return-checkbox-wrapper i {
            display: block;
        }

        .return-modal-footer {
            padding: 20px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark-mode .return-info-alert {
            background: rgba(3, 105, 161, 0.1);
            border-color: #0369a1;
            color: #7dd3fc;
        }

        .dark-mode .return-item-card {
            background: #111827;
            border-color: #1f2937;
        }

        .dark-mode .return-item-card:hover {
            border-color: #3b82f6;
            background: #1e293b;
        }

        .dark-mode .return-item-card.selected {
            background: #1e3a8a;
            border-color: #3b82f6;
        }

        .dark-mode .return-item-name {
            color: #f1f5f9;
        }

        .dark-mode .return-modal-footer {
            border-top-color: #1f2937;
        }

        .return-refund-details {
            display: none;
            margin-top: 0;
            padding-top: 15px;
            border-top: 1px solid #e0f2fe;
            height: auto;
        }

        .selected .return-refund-details {
            display: flex;
            flex-direction: column;
        }

        /* Return Steps */
        .return-step {
            display: none;
        }

        .return-step.active {
            display: block;
        }

        .refund-source-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .source-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .source-card:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .source-card.active {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .source-card i {
            font-size: 1.5rem;
            color: #64748b;
            margin-bottom: 10px;
            display: block;
        }

        .source-card.active i {
            color: #3b82f6;
        }

        .source-card span {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
        }

        .return-summary-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .warning-alert {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 8px;
            padding: 12px 16px;
            color: #92400e;
            font-size: 0.85rem;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .dark-mode .source-card,
        .dark-mode .return-summary-card {
            background: #111827;
            border-color: #1f2937;
        }

        .dark-mode .source-card:hover {
            background: #1e293b;
        }

        .dark-mode .source-card.active {
            background: #1e3a8a;
        }

        .dark-mode .source-card span {
            color: #cbd5e1;
        }

        .dark-mode .summary-item {
            border-bottom-color: #1f2937;
        }

        .dark-mode .warning-alert {
            background: rgba(146, 64, 14, 0.1);
            border-color: #92400e;
            color: #fcd34d;
        }

        .dark-mode .dark-mode-text-white {
            color: #f1f5f9 !important;
        }

        .return-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .return-input-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            width: 100px;
        }

        .return-input-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }

        .return-input-prefix {
            background: #f8fafc;
            padding: 5px 10px;
            border-right: 1px solid #cbd5e1;
            font-size: 0.85rem;
            color: #64748b;
        }

        .return-input {
            border: none;
            padding: 5px 12px;
            width: 100%;
            font-weight: 700;
            text-align: right;
            outline: none;
        }

        .dark-mode .return-refund-details {
            border-top-color: #334155;
        }

        .dark-mode .return-input-wrapper {
            background: #1e293b;
            border-color: #334155;
        }

        .dark-mode .return-input-prefix {
            background: #334155;
            border-right-color: #475569;
            color: #94a3b8;
        }

        .dark-mode .return-input {
            background: transparent;
            color: white;
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
                <a href="{{ route('admin.orders.page') }}" class="nav-pill active"><i class="bi bi-list-ul"></i>
                    Orders</a>
                <a href="{{ route('admin.pending_orders.page') }}" class="nav-pill">
                    <i class="bi bi-hourglass-split"></i> Pending
                    @if (isset($pending_count) && $pending_count > 0)
                        <span class="nav-badge">{{ $pending_count }}</span>
                    @endif
                </a>

                <div class="ms-lg-auto d-flex flex-wrap align-items-center gap-2 right-actions">
                    <button class="theme-toggle" id="posThemeToggle" type="button" aria-label="Toggle dark mode"
                        style="background:transparent;border:none;color:white;font-size:1.2rem;cursor:pointer;">
                        <i class="bi bi-moon-stars" id="posThemeToggleIcon"></i>
                    </button>
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
                    <p class="mt-3 text-muted">No completed orders found.</p>
                </div>
            @else
                @foreach ($orders as $order)
                    @include('admin.orders.order_card_completed', ['order' => $order])
                @endforeach
            @endif
        </div>
    </div>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-body p-0">
                    <div class="receipt-modal-content" id="receiptContent">
                        <!-- Content will be populated by JS -->
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary px-4 fw-bold" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Return Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-counterclockwise text-primary"></i> Process Return
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="px-3 pt-1">
                    <small class="text-muted" id="returnOrderNumber">Order #6f992d</small>
                </div>
                <div class="modal-body p-0">
                    <!-- History Section -->
                    <div id="returnHistorySection" class="p-3 bg-light border-bottom" style="display: none; max-height: 200px; overflow-y: auto;">
                        <h6 class="fw-bold text-muted mb-2 small d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history"></i> Previous Return History
                        </h6>
                        <div id="returnHistoryList">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Step 1: Select Items -->
                    <div class="return-step active" id="returnStep1">
                        <div class="return-modal-content">
                            <div class="return-info-alert">
                                <i class="bi bi-info-circle-fill"></i>
                                <div>Select items being returned. Adjust quantity and amount as needed.</div>
                            </div>

                            <div id="returnItemsList">
                                <!-- Items will be populated by JS -->
                            </div>
                        </div>
                        <div class="return-modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                            <div class="fw-bold text-muted small" id="selectedCountText">Items selected: 0</div>
                            <button type="button" class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center gap-2" id="btnNextReturn" disabled>
                                Next <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Refund Source & Summary -->
                    <div class="return-step" id="returnStep2">
                        <div class="return-modal-content">
                            <div class="mb-4">
                                <h6 class="fw-bold text-muted mb-3 small">1. Select Refund Source</h6>
                                <div class="refund-source-grid">
                                    <div class="source-card active" data-source="Cash">
                                        <i class="bi bi-cash-stack"></i>
                                        <span>Cash</span>
                                    </div>
                                    <div class="source-card" data-source="E-Cash">
                                        <i class="bi bi-phone"></i>
                                        <span>E-Cash</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-muted mb-3 small">2. Review Summary</h6>
                                <div class="return-summary-card">
                                    <div id="summaryItemsList">
                                        <!-- Summary items will be populated by JS -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end mt-4">
                                        <div>
                                            <div class="fw-bold text-muted small text-uppercase">Final Refund Amount</div>
                                            <div class="text-primary fw-bold small" id="summarySourceText">VIA CASH</div>
                                        </div>
                                        <div class="text-primary h3 fw-bold mb-0" id="finalRefundAmount">₱0.00</div>
                                    </div>
                                </div>
                            </div>

                            <div class="warning-alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <div>This is a manual <strong id="warningSourceText">cash</strong> adjustment. Ensure the external transaction is completed before clicking confirm.</div>
                            </div>
                        </div>
                        <div class="return-modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                            <button type="button" class="btn btn-light px-4 py-2 fw-bold" id="btnBackReturn">Edit Items</button>
                            <button type="button" class="btn btn-primary px-4 py-2 fw-bold" id="btnConfirmReturn">Process Return</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

        // --- Exclude Sales Logic ---
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('exclude-sales-toggle')) {
                const orderNumber = e.target.getAttribute('data-order');
                const card = document.getElementById('order-card-' + orderNumber);
                const profits = card.querySelectorAll('.profit-info');

                if (e.target.checked) {
                    profits.forEach(p => p.style.display = 'none');
                } else {
                    profits.forEach(p => p.style.display = 'block');
                }
            }
        });

        // --- Print Logic ---
        const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        const receiptContent = document.getElementById('receiptContent');

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-print-receipt');
            if (btn) {
                const orderData = JSON.parse(btn.getAttribute('data-order-json'));
                renderReceipt(orderData);
                receiptModal.show();
            }
        });

        function renderReceipt(order) {
            let itemsHtml = '';
            order.items.forEach(item => {
                const isReturned = (parseFloat(item.returned_quantity) >= parseFloat(item.quantity));
                itemsHtml += `
                    <div class="receipt-item-row" style="${isReturned ? 'opacity: 0.6; text-decoration: line-through;' : ''}">
                        <div>
                            <span class="receipt-item-name">${item.product?.product_name || 'Product'}</span>
                            <span class="receipt-item-qty">${parseFloat(item.quantity)} x ₱${parseFloat(item.total_price / item.quantity).toFixed(2)}</span>
                        </div>
                        <div class="fw-bold">₱${parseFloat(item.total_price).toFixed(2)}</div>
                    </div>
                `;
            });

            receiptContent.innerHTML = `
                <div class="receipt-header">
                    <h2>SAMMER'S STORE</h2>
                    <p>Order Details</p>
                </div>
                <div class="receipt-info">
                    <div>
                        <div>${new Date(order.updated_at).toLocaleDateString()}</div>
                        <div>Order #: ${order.order_number}</div>
                        <div>Customer: ${order.customer_name || 'New Customer'}</div>
                    </div>
                    <div class="text-end">
                        <div>${new Date(order.updated_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                    </div>
                </div>
                <div class="receipt-items">
                    ${itemsHtml}
                </div>
                <div class="receipt-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₱${parseFloat(order.original_total + (parseFloat(order.discount_price) || 0)).toFixed(2)}</span>
                    </div>
                    ${order.discount_price > 0 ? `
                        <div class="summary-row">
                            <span style="color: #ef4444;">Discount</span>
                            <span style="color: #ef4444;">-₱${parseFloat(order.discount_price).toFixed(2)}</span>
                        </div>
                        ` : ''}

                    ${order.total_refunded > 0 ? `
                        <div class="summary-row fw-bold border-top pt-2 mt-2" style="border-top-style: dashed !important;">
                            <span>Original Total</span>
                            <span>₱${parseFloat(order.original_total).toFixed(2)}</span>
                        </div>
                        <div class="summary-row" style="color: #ef4444;">
                            <span>Refunds</span>
                            <span>-₱${parseFloat(order.total_refunded).toFixed(2)}</span>
                        </div>
                    ` : ''}

                    <div class="summary-row total">
                        <span>${order.total_refunded > 0 ? 'Net Total' : 'Total'}</span>
                        <span>₱${parseFloat(order.total_amount).toFixed(2)}</span>
                    </div>
                    <div class="summary-row mt-2">
                        <span>Cash</span>
                        <span>₱${parseFloat(order.payment_amount || 0).toFixed(2)}</span>
                    </div>
                    <div class="summary-row">
                        <span>Change Given</span>
                        <span>₱${parseFloat(order.change_amount || 0).toFixed(2)}</span>
                    </div>
                </div>
                <div class="receipt-footer">
                    Thank you!
                </div>
            `;
        }

        // --- Return Logic ---
        const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
        const returnItemsList = document.getElementById('returnItemsList');
        const returnOrderNumberText = document.getElementById('returnOrderNumber');
        const selectedCountText = document.getElementById('selectedCountText');
        const btnNextReturn = document.getElementById('btnNextReturn');

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-process-return');
            if (btn) {
                const orderData = JSON.parse(btn.getAttribute('data-order-json'));
                renderReturnItems(orderData);
                returnOrderNumberText.innerText = `Order ${orderData.order_number}`;
                updateSelectedCount();
                returnModal.show();
            }
        });

        function renderReturnItems(order) {
            // Handle History
            const historySection = document.getElementById('returnHistorySection');
            const historyList = document.getElementById('returnHistoryList');
            
            if (order.returns && order.returns.length > 0) {
                historySection.style.display = 'block';
                let historyHtml = '';
                order.returns.forEach(ret => {
                    const date = new Date(ret.created_at).toLocaleString();
                    let itemsDetail = '';
                    ret.items.forEach(item => {
                        itemsDetail += `<li class="small">${parseFloat(item.quantity)}x ${item.order_item?.product?.product_name || 'Product'} <span class="text-danger">(-₱${parseFloat(item.refund_amount).toFixed(2)})</span></li>`;
                    });
                    historyHtml += `
                        <div class="mb-2 pb-2 border-bottom last-child-border-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-bold text-muted">${date}</span>
                                <span class="badge bg-light text-dark border">₱${parseFloat(ret.refund_amount).toFixed(2)}</span>
                            </div>
                            <ul class="mb-0 ps-3">
                                ${itemsDetail}
                            </ul>
                        </div>
                    `;
                });
                historyList.innerHTML = historyHtml;
            } else {
                historySection.style.display = 'none';
            }

            let itemsHtml = '';
            order.items.forEach(item => {
                const productImg = item.product?.product_image 
                    ? `{{ asset('images/products') }}/${item.product.product_image}` 
                    : `{{ asset('assets/img/no-image.png') }}`;
                
                const unitPrice = parseFloat(item.total_price / item.quantity);
                const returnedQty = parseFloat(item.returned_quantity || 0);
                const remainingQty = parseFloat(item.quantity) - returnedQty;
                const isFullyReturned = remainingQty <= 0;

                itemsHtml += `
                    <div class="return-item-card flex-column align-items-start p-3 ${isFullyReturned ? 'opacity-50 pointer-events-none' : ''}" 
                         data-item-id="${item.id}" 
                         data-max-qty="${remainingQty}"
                         style="${isFullyReturned ? 'cursor: not-allowed;' : ''}">
                        <div class="d-flex align-items-center w-100 gap-3 mb-2">
                            <img src="${productImg}" class="return-item-img" alt="Product" style="width: 60px; height: 60px;">
                            <div class="return-item-details">
                                <div class="return-item-name" style="font-size: 1.1rem; font-weight: 700;">${item.product?.product_name || 'Product'}</div>
                                <div class="return-item-meta" style="font-size: 0.9rem;">
                                    Original Qty: ${parseFloat(item.quantity)} • ₱${unitPrice.toFixed(2)}/unit
                                    ${returnedQty > 0 ? `<br><span class="text-danger">(${returnedQty} already returned)</span>` : ''}
                                </div>
                            </div>
                            ${isFullyReturned ? `
                                <div class="ms-auto"><span class="badge bg-secondary">Fully Returned</span></div>
                            ` : `
                                <div class="ms-auto return-checkbox-wrapper" style="width: 28px; height: 28px;">
                                    <i class="bi bi-check-lg" style="font-size: 1rem;"></i>
                                </div>
                            `}
                        </div>

                        <div class="return-refund-details w-100 mt-2 pt-3" style="border-top: 1px solid #e0f2fe;">
                            @php $fractionalQtys = [0.25, 0.33, 0.5, 0.75]; @endphp
                            ${ (remainingQty > 1 && ![0.25, 0.33, 0.5, 0.75].includes(Math.round(remainingQty * 100) / 100)) ? `
                            <div class="return-input-group justify-content-between">
                                <div class="return-input-label" style="font-size: 0.85rem; color: #64748b;">RETURN QTY</div>
                                <div class="return-input-wrapper" style="max-width: 200px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                    <div class="return-input-prefix bg-white"><i class="bi bi-box-seam"></i></div>
                                    <input type="number" class="return-input qty-input" value="${remainingQty}" max="${remainingQty}" min="0.01" step="any" data-unit-price="${unitPrice}">
                                </div>
                            </div>
                            ` : `<input type="hidden" class="qty-input" value="${remainingQty}" data-unit-price="${unitPrice}">` }
                            <div class="return-input-group justify-content-between">
                                <div class="return-input-label" style="font-size: 0.85rem; color: #64748b;">REFUND AMT.</div>
                                <div class="return-input-wrapper" style="max-width: 200px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                    <div class="return-input-prefix bg-white"><span style="font-family: serif; font-weight: bold; color: #64748b;">₱</span></div>
                                    <input type="number" class="return-input refund-input" value="${(remainingQty * unitPrice).toFixed(2)}" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            returnItemsList.innerHTML = itemsHtml;

            // Add click handlers to cards
            const cards = returnItemsList.querySelectorAll('.return-item-card:not(.opacity-50)');
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.closest('.return-input-group')) return;
                    this.classList.toggle('selected');
                    updateSelectedCount();
                });
            });

            // Auto-calculate refund when qty changes
            const qtyInputs = returnItemsList.querySelectorAll('.qty-input');
            qtyInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const card = this.closest('.return-item-card');
                    const refundInput = card.querySelector('.refund-input');
                    const unitPrice = parseFloat(this.getAttribute('data-unit-price'));
                    const maxQty = parseFloat(card.getAttribute('data-max-qty'));
                    let qty = parseFloat(this.value) || 0;
                    
                    if (qty > maxQty) {
                        qty = maxQty;
                        this.value = maxQty;
                    }

                    refundInput.value = (qty * unitPrice).toFixed(2);
                });
            });
        }

        function updateSelectedCount() {
            const selectedCount = returnItemsList.querySelectorAll('.return-item-card.selected').length;
            selectedCountText.innerText = `Items selected: ${selectedCount}`;
            btnNextReturn.disabled = selectedCount === 0;
        }

        // --- Step Transitions ---
        const btnNext = document.getElementById('btnNextReturn');
        const btnBack = document.getElementById('btnBackReturn');
        const step1 = document.getElementById('returnStep1');
        const step2 = document.getElementById('returnStep2');

        btnNext.addEventListener('click', () => {
            populateSummary();
            step1.classList.remove('active');
            step2.classList.add('active');
        });

        btnBack.addEventListener('click', () => {
            step2.classList.remove('active');
            step1.classList.add('active');
        });

        // --- Refund Source Selection ---
        const sourceCards = document.querySelectorAll('.source-card');
        const summarySourceText = document.getElementById('summarySourceText');
        const warningSourceText = document.getElementById('warningSourceText');

        sourceCards.forEach(card => {
            card.addEventListener('click', function() {
                sourceCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                const source = this.getAttribute('data-source');
                summarySourceText.innerText = `VIA ${source.toUpperCase()}`;
                warningSourceText.innerText = source.toLowerCase();
            });
        });

        function populateSummary() {
            const selectedCards = returnItemsList.querySelectorAll('.return-item-card.selected');
            const summaryList = document.getElementById('summaryItemsList');
            const finalAmountEl = document.getElementById('finalRefundAmount');
            
            let html = '';
            let totalRefund = 0;

            selectedCards.forEach(card => {
                const name = card.querySelector('.return-item-name').innerText;
                const qty = card.querySelector('.qty-input')?.value || 1;
                const refund = parseFloat(card.querySelector('.refund-input').value);
                
                totalRefund += refund;

                html += `
                    <div class="summary-item">
                        <div>
                            <div class="fw-bold text-dark dark-mode-text-white" style="font-size: 0.95rem;">${name}</div>
                            <div class="text-muted small">QTY: ${qty}</div>
                        </div>
                        <div class="fw-bold">₱${refund.toFixed(2)}</div>
                    </div>
                `;
            });

            summaryList.innerHTML = html;
            finalAmountEl.innerText = `₱${totalRefund.toFixed(2)}`;
        }

        // Reset steps when modal is closed
        document.getElementById('returnModal').addEventListener('hidden.bs.modal', function () {
            step2.classList.remove('active');
            step1.classList.add('active');
        });

        // --- Process Return AJAX ---
        const btnConfirmReturn = document.getElementById('btnConfirmReturn');
        btnConfirmReturn.addEventListener('click', function() {
            const selectedCards = returnItemsList.querySelectorAll('.return-item-card.selected');
            const items = [];
            let totalRefund = 0;

            selectedCards.forEach(card => {
                const id = card.getAttribute('data-item-id');
                const qty = card.querySelector('.qty-input').value;
                const refund = card.querySelector('.refund-input').value;
                
                totalRefund += parseFloat(refund);
                items.push({
                    id: id,
                    quantity: qty,
                    refund_amount: refund
                });
            });

            const source = document.querySelector('.source-card.active').getAttribute('data-source');
            const orderNumber = returnOrderNumberText.innerText.replace('Order ', '');

            btnConfirmReturn.disabled = true;
            btnConfirmReturn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;

            fetch('{{ route("admin.orders.process_return") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    order_number: orderNumber,
                    refund_source: source,
                    refund_amount: totalRefund,
                    items: items
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    btnConfirmReturn.disabled = false;
                    btnConfirmReturn.innerText = 'Process Return';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
                btnConfirmReturn.disabled = false;
                btnConfirmReturn.innerText = 'Process Return';
            });
        });
    </script>
</body>

</html>
