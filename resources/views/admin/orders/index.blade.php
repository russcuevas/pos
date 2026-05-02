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
                itemsHtml += `
                    <div class="receipt-item-row">
                        <div>
                            <span class="receipt-item-name">${item.product?.product_name || 'Product'}</span>
                            <span class="receipt-item-qty">${item.quantity} x ₱${parseFloat(item.total_price / item.quantity).toFixed(2)}</span>
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
                        <span>₱${parseFloat(order.original_total).toFixed(2)}</span>
                    </div>
                    ${order.discount_price > 0 ? `
                        <div class="summary-row">
                            <span style="color: #ef4444;">Discount</span>
                            <span style="color: #ef4444;">-₱${parseFloat(order.discount_price).toFixed(2)}</span>
                        </div>
                        ` : ''}
                    <div class="summary-row total">
                        <span>Total</span>
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
    </script>
</body>

</html>
