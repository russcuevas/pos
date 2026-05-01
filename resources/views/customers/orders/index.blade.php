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

        .status-ready {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-completed {
            background: #e0f2fe;
            color: #0284c7;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
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
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s;
        }

        .footer-link:hover {
            opacity: 0.8;
        }

        .footer-link-add {
            color: #10b981;
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

        .chat-messages-container::-webkit-scrollbar {
            width: 5px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
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
            align-self: flex-end;
            text-align: right;
        }

        .chat-bubble-admin {
            background: #f1f5f9;
            color: #475569;
            align-self: flex-start;
            text-align: left;
        }

        .chat-time {
            font-size: 0.65rem;
            color: #94a3b8;
            margin-top: 5px;
            display: block;
            text-align: right;
            width: 100%;
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

        .chat-input:focus {
            border-color: #0284c7;
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

        .dark-mode .product-item {
            border-bottom-color: #374151;
        }

        .dark-mode .product-item:hover {
            background: #1f2937;
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
            margin-bottom: 2px;
        }

        .dark-mode .product-item-name {
            color: #f1f5f9;
        }

        .product-item-price {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .dark-mode .product-item-price {
            color: #94a3b8;
        }

        /* Dark Mode Chat Overrides */
        .dark-mode .chat-section {
            background: #111827;
            border-top-color: #1f2937;
        }

        .dark-mode .chat-bubble-customer {
            background: #1e3a8a;
            color: #f1f5f9;
            border: 1px solid #1e40af;
        }

        .dark-mode .chat-bubble-admin {
            background: #1f2937;
            color: #cbd5e1;
            border: 1px solid #374151;
        }

        .dark-mode .chat-time {
            color: #64748b;
        }

        .dark-mode .chat-input {
            background: #1f2937;
            border-color: #374151;
            color: #f1f5f9;
        }

        .dark-mode .chat-input:focus {
            border-color: #0284c7;
        }

        @keyframes highlight-green {
            0% {
                background-color: rgba(16, 185, 129, 0.2);
            }

            100% {
                background-color: transparent;
            }
        }

        .item-added-animate {
            animation: highlight-green 2s ease-out;
        }

        .modal-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10;
            border-radius: 8px;
        }

        .dark-mode .modal-loading-overlay {
            background: rgba(17, 24, 39, 0.7);
        }
    </style>
</head>

<body class="pos-page">

    @include('customers.components.navbar')

    <div class="pos-wrap">
        <div class="orders-container">
            @if ($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-receipt fs-1 text-muted opacity-50"></i>
                    <p class="mt-3 text-muted">You haven't placed any orders yet.</p>
                    <a href="{{ route('customers.home.page') }}" class="btn btn-primary mt-2">Browse Products</a>
                </div>
            @else
                @foreach ($orders as $order)
                    <div class="order-card">
                        <div class="order-card-header">
                            <div>
                                <div class="order-number">Order {{ $order->order_number }}</div>
                                <div class="order-date">{{ $order->created_at->format('n/j/Y, g:i:s A') }}</div>
                            </div>
                            <div class="order-total" id="order-total-{{ $order->order_number }}">
                                ₱{{ number_format($order->total_amount, 2) }}</div>
                        </div>

                        <div class="order-card-body">
                            @php
                                $statusClass = '';
                                $statusLabel = $order->status;
                                switch (strtolower($order->status)) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        $statusLabel = 'Pending Confirmation';
                                        break;
                                    case 'ready for pickup':
                                        $statusClass = 'status-ready';
                                        break;
                                    case 'completed':
                                        $statusClass = 'status-completed';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'status-cancelled';
                                        break;
                                    default:
                                        $statusClass = 'status-pending';
                                }
                            @endphp
                            <div class="status-badge {{ $statusClass }}" id="status-badge-{{ $order->order_number }}">
                                {{ $statusLabel }}
                            </div>

                            <div class="order-items-list" id="items-list-{{ $order->order_number }}">
                                @foreach ($order->items as $item)
                                    <div class="order-item-row" id="item-row-{{ $item->id }}">
                                        <div class="item-info">
                                            @if (strtolower($order->status) === 'pending')
                                                <div class="item-qty-wrap">
                                                    <button class="btn-qty-subtle btn-order-qty-adj"
                                                        data-order-id="{{ $item->id }}" data-action="decrement">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <span class="fw-bold" id="item-qty-{{ $item->id }}"
                                                        style="min-width: 15px; text-align: center;">{{ (int) $item->quantity }}</span>
                                                    <button class="btn-qty-subtle btn-order-qty-adj"
                                                        data-order-id="{{ $item->id }}" data-action="increment">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="text-muted fw-bold"
                                                        style="font-size: 0.85rem;">{{ (int) $item->quantity }}x</span>
                                                </div>
                                            @endif
                                            <span
                                                class="item-name">{{ $item->product->product_name ?? 'Product' }}</span>
                                        </div>
                                        <div class="text-end">
                                            @php
                                                $product = $item->product;
                                                $wholesaleBundles = 0;
                                                $regularItems = 0;
                                                if (
                                                    $product &&
                                                    $product->whole_sale_qty > 0 &&
                                                    $item->quantity >= $product->whole_sale_qty
                                                ) {
                                                    $wholesaleBundles = (int) floor(
                                                        $item->quantity / $product->whole_sale_qty,
                                                    );
                                                    $regularItems = (int) ($item->quantity % $product->whole_sale_qty);
                                                }
                                            @endphp
                                            <div id="wholesale-wrap-{{ $item->id }}"
                                                style="display: {{ $wholesaleBundles > 0 ? 'block' : 'none' }};">
                                                <span class="wholesale-badge">Wholesale Applied</span>
                                            </div>
                                            <div class="item-price" id="item-price-{{ $item->id }}">
                                                ₱{{ number_format($item->total_price, 2) }}</div>
                                            <small class="text-muted wholesale-details"
                                                id="wholesale-details-{{ $item->id }}"
                                                style="display: {{ $wholesaleBundles > 0 ? 'block' : 'none' }};">
                                                <span id="bundles-{{ $item->id }}">{{ $wholesaleBundles }}</span>x
                                                (Whole)
                                                <span id="regular-wrap-{{ $item->id }}"
                                                    style="display: {{ $regularItems > 0 ? 'inline' : 'none' }};">
                                                    <br><span
                                                        id="regular-{{ $item->id }}">{{ $regularItems }}</span>x
                                                    (Retail)
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($order->note)
                                <div class="order-note-section">
                                    <strong>Your Note:</strong> {{ $order->note }}
                                </div>
                            @endif
                        </div>

                        <div class="order-card-footer">
                            <a href="javascript:void(0)" class="footer-link btn-toggle-chat"
                                data-order-number="{{ $order->order_number }}">
                                <i class="bi bi-chat-dots-fill"></i>
                                Chat (<span
                                    id="chat-count-{{ $order->order_number }}">{{ $order->chats_count }}</span>)
                            </a>

                            @if (strtolower($order->status) === 'pending')
                                <a href="javascript:void(0)" class="footer-link footer-link-add btn-open-product-modal"
                                    data-order-number="{{ $order->order_number }}"
                                    id="btn-add-{{ $order->order_number }}">
                                    <i class="bi bi-plus-circle-fill"></i>
                                    Add
                                </a>
                            @endif
                        </div>

                        <!-- Chat Section -->
                        <div class="chat-section" id="chat-{{ $order->order_number }}">
                            <div class="chat-messages-container" id="messages-container-{{ $order->order_number }}">
                                @foreach ($order->chats as $chat)
                                    @php
                                        $isCustomer = !($chat->customer_id === null && $chat->allowed !== null);
                                    @endphp
                                    <div
                                        class="chat-bubble {{ $isCustomer ? 'chat-bubble-customer' : 'chat-bubble-admin' }}">
                                        <div class="chat-text">{{ $chat->message }}</div>
                                        <span class="chat-time">{{ $chat->created_at->format('g:i:s A') }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if (strtolower($order->status) !== 'completed')
                                <div class="chat-input-group" id="chat-input-group-{{ $order->order_number }}">
                                    <input type="text" class="chat-input" placeholder="Type a message..."
                                        id="input-{{ $order->order_number }}">
                                    <button class="btn-send-chat"
                                        data-order-number="{{ $order->order_number }}">Send</button>
                                </div>
                            @else
                                <div class="text-center text-muted small py-2"
                                    id="chat-disabled-{{ $order->order_number }}">
                                    Chat is disabled for completed orders.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>



    @include('customers.components.cart-sidebar')

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Select Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <div class="modal-loading-overlay" id="modalLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="modalProductSearch" class="form-control ps-5 rounded-pill"
                            placeholder="Search products...">
                    </div>
                    <div class="product-list-container" id="modalProductList">
                        @foreach ($products as $product)
                            <a href="javascript:void(0)" class="product-item modal-product-btn"
                                data-id="{{ $product->id }}" data-name="{{ strtolower($product->product_name) }}">
                                @if ($product->product_image)
                                    <img src="{{ asset('images/products/' . $product->product_image) }}"
                                        class="product-item-img">
                                @else
                                    <div
                                        class="product-item-img d-flex align-items-center justify-content-center bg-light fs-4">
                                        📦</div>
                                @endif
                                <div class="product-item-info">
                                    <div class="product-item-name">{{ $product->product_name }}</div>
                                    <div class="product-item-price">₱{{ number_format($product->selling_price, 2) }}
                                    </div>
                                </div>
                                <i class="bi bi-plus-lg text-primary ms-2"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

    @include('customers.components.cart-scripts')

    <script>
        // Chat Functionality
        let chatPollers = {};

        function startChatPolling(orderNumber) {
            if (chatPollers[orderNumber]) return;

            chatPollers[orderNumber] = setInterval(async () => {
                try {
                    const response = await fetch(`/customers/orders/messages/${orderNumber}`);
                    const data = await response.json();
                    if (data.success) {
                        const container = document.getElementById(`messages-container-${orderNumber}`);
                        const countSpan = document.getElementById(`chat-count-${orderNumber}`);

                        // Update chat count
                        if (countSpan) countSpan.textContent = data.chats.length;

                        // Check if we need to update messages
                        const currentCount = container.querySelectorAll('.chat-bubble').length;
                        if (data.chats.length > currentCount) {
                            // Clear and re-render (simplest for now)
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
                    console.error('Polling error:', e);
                }
            }, 5000); // Poll every 5 seconds
        }

        document.addEventListener('click', async (e) => {
            // Toggle Chat
            if (e.target.closest('.btn-toggle-chat')) {
                const btn = e.target.closest('.btn-toggle-chat');
                const orderNumber = btn.dataset.orderNumber;
                const chatSection = document.getElementById(`chat-${orderNumber}`);

                if (chatSection.style.display === 'block') {
                    chatSection.style.display = 'none';
                    clearInterval(chatPollers[orderNumber]);
                    delete chatPollers[orderNumber];
                } else {
                    document.querySelectorAll('.chat-section').forEach(s => {
                        s.style.display = 'none';
                        // Stop other pollers
                        const otherNum = s.id.replace('chat-', '');
                        clearInterval(chatPollers[otherNum]);
                        delete chatPollers[otherNum];
                    });
                    chatSection.style.display = 'block';
                    const container = chatSection.querySelector('.chat-messages-container');
                    container.scrollTop = container.scrollHeight;
                    startChatPolling(orderNumber);
                }
            }

            // Send Chat (Live Update)
            if (e.target.closest('.btn-send-chat')) {
                const btn = e.target.closest('.btn-send-chat');
                const orderNumber = btn.dataset.orderNumber;
                const input = document.getElementById(`input-${orderNumber}`);
                const message = input.value.trim();

                if (!message) return;

                try {
                    const response = await fetch("{{ route('customers.orders.send_chat') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_number: orderNumber,
                            message: message
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        input.value = '';
                        // Instantly append message to UI
                        const container = document.getElementById(`messages-container-${orderNumber}`);
                        const bubble = document.createElement('div');
                        bubble.className = 'chat-bubble chat-bubble-customer';
                        bubble.innerHTML =
                            `<div class="chat-text">${data.chat.message}</div><span class="chat-time">${data.chat.time}</span>`;
                        container.appendChild(bubble);
                        container.scrollTop = container.scrollHeight;

                        // Update count locally
                        const countSpan = document.getElementById(`chat-count-${orderNumber}`);
                        if (countSpan) countSpan.textContent = parseInt(countSpan.textContent) + 1;
                    } else {
                        if (typeof notyf !== 'undefined') notyf.error(data.message);
                    }
                } catch (error) {
                    console.error('Error sending chat:', error);
                }
            }
        });

        // Quantity Update Functionality (Live Update)
        document.addEventListener('click', async (e) => {
            if (e.target.closest('.btn-order-qty-adj')) {
                const btn = e.target.closest('.btn-order-qty-adj');
                const orderId = btn.dataset.orderId;
                const action = btn.dataset.action;
                const orderCard = btn.closest('.order-card');
                const orderNumber = orderCard.querySelector('.order-number').textContent.replace('Order ', '');

                try {
                    const response = await fetch("{{ route('customers.orders.update_qty') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            action: action
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        if (data.message === 'Item removed from order.') {
                            const row = document.getElementById(`item-row-${orderId}`);
                            row.remove();
                            // If no more items, maybe reload or show empty
                            if (orderCard.querySelectorAll('.order-item-row').length === 0) {
                                window.location.reload();
                            }
                        } else {
                            // Update values in DOM
                            const qtySpan = document.getElementById(`item-qty-${orderId}`);
                            const priceSpan = document.getElementById(`item-price-${orderId}`);
                            const totalSpan = document.getElementById(`order-total-${orderNumber}`);

                            if (qtySpan) qtySpan.textContent = data.new_qty;
                            if (priceSpan) priceSpan.textContent = `₱${data.new_line_total}`;
                            if (totalSpan) totalSpan.textContent = `₱${data.new_order_total}`;

                            // Update Wholesale UI
                            const wWrap = document.getElementById(`wholesale-wrap-${orderId}`);
                            const wDetails = document.getElementById(`wholesale-details-${orderId}`);
                            const bundlesSpan = document.getElementById(`bundles-${orderId}`);
                            const regularSpan = document.getElementById(`regular-${orderId}`);
                            const regularWrap = document.getElementById(`regular-wrap-${orderId}`);

                            if (data.is_wholesale) {
                                if (wWrap) wWrap.style.display = 'block';
                                if (wDetails) wDetails.style.display = 'block';
                                if (bundlesSpan) bundlesSpan.textContent = data.wholesale_bundles;
                                if (regularSpan) regularSpan.textContent = data.regular_items;
                                if (regularWrap) regularWrap.style.display = data.regular_items > 0 ? 'inline' :
                                    'none';
                            } else {
                                if (wWrap) wWrap.style.display = 'none';
                                if (wDetails) wDetails.style.display = 'none';
                            }
                        }
                    } else {
                        if (typeof notyf !== 'undefined') notyf.error(data.message);
                    }
                } catch (error) {
                    console.error('Error updating order qty:', error);
                }
            }
        });

        // Product Modal Functionality
        let currentOrderNumber = null;
        const productModal = new bootstrap.Modal(document.getElementById('productModal'));

        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-open-product-modal')) {
                currentOrderNumber = e.target.closest('.btn-open-product-modal').dataset.orderNumber;
                productModal.show();
            }

            if (e.target.closest('.modal-product-btn')) {
                const productId = e.target.closest('.modal-product-btn').dataset.id;
                addProductToOrder(currentOrderNumber, productId);
            }
        });

        async function addProductToOrder(orderNumber, productId) {
            const modalLoading = document.getElementById('modalLoading');
            modalLoading.style.display = 'flex';

            try {
                const response = await fetch("{{ route('customers.orders.add_item') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_number: orderNumber,
                        product_id: productId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    if (typeof notyf !== 'undefined') notyf.success(data.message);

                    const listContainer = document.getElementById(`items-list-${orderNumber}`);
                    const totalSpan = document.getElementById(`order-total-${orderNumber}`);

                    if (totalSpan) totalSpan.textContent = `₱${data.new_order_total}`;

                    if (data.is_new) {
                        // Create new row
                        const row = document.createElement('div');
                        row.className = 'order-item-row item-added-animate';
                        row.id = `item-row-${data.item_id}`;

                        let wholesaleHtml = `
                            <div id="wholesale-wrap-${data.item_id}" style="display: ${data.is_wholesale ? 'block' : 'none'};">
                                <span class="wholesale-badge">Wholesale Applied</span>
                            </div>
                            <div class="item-price" id="item-price-${data.item_id}">₱${data.new_line_total}</div>
                            <small class="text-muted wholesale-details" id="wholesale-details-${data.item_id}" style="display: ${data.is_wholesale ? 'block' : 'none'};">
                                <span id="bundles-${data.item_id}">${data.wholesale_bundles}</span>x (Whole)
                                <span id="regular-wrap-${data.item_id}" style="display: ${data.regular_items > 0 ? 'inline' : 'none'};">
                                    <br><span id="regular-${data.item_id}">${data.regular_items}</span>x (Retail)
                                </span>
                            </small>`;

                        row.innerHTML = `
                            <div class="item-info">
                                <div class="item-qty-wrap">
                                    <button class="btn-qty-subtle btn-order-qty-adj" data-order-id="${data.item_id}" data-action="decrement">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <span class="fw-bold" id="item-qty-${data.item_id}" style="min-width: 15px; text-align: center;">${data.new_qty}</span>
                                    <button class="btn-qty-subtle btn-order-qty-adj" data-order-id="${data.item_id}" data-action="increment">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                <span class="item-name">${data.product_name}</span>
                            </div>
                            <div class="text-end">${wholesaleHtml}</div>`;

                        listContainer.appendChild(row);
                    } else {
                        // Update existing row
                        const qtySpan = document.getElementById(`item-qty-${data.item_id}`);
                        const priceSpan = document.getElementById(`item-price-${data.item_id}`);
                        const row = document.getElementById(`item-row-${data.item_id}`);

                        if (qtySpan) qtySpan.textContent = data.new_qty;
                        if (priceSpan) priceSpan.textContent = `₱${data.new_line_total}`;

                        if (row) {
                            row.classList.remove('item-added-animate');
                            void row.offsetWidth; // trigger reflow
                            row.classList.add('item-added-animate');
                        }

                        // Update Wholesale UI
                        const wWrap = document.getElementById(`wholesale-wrap-${data.item_id}`);
                        const wDetails = document.getElementById(`wholesale-details-${data.item_id}`);
                        const bundlesSpan = document.getElementById(`bundles-${data.item_id}`);
                        const regularSpan = document.getElementById(`regular-${data.item_id}`);
                        const regularWrap = document.getElementById(`regular-wrap-${data.item_id}`);

                        if (data.is_wholesale) {
                            if (wWrap) wWrap.style.display = 'block';
                            if (wDetails) wDetails.style.display = 'block';
                            if (bundlesSpan) bundlesSpan.textContent = data.wholesale_bundles;
                            if (regularSpan) regularSpan.textContent = data.regular_items;
                            if (regularWrap) regularWrap.style.display = data.regular_items > 0 ? 'inline' : 'none';
                        } else {
                            if (wWrap) wWrap.style.display = 'none';
                            if (wDetails) wDetails.style.display = 'none';
                        }
                    }
                } else {
                    if (typeof notyf !== 'undefined') notyf.error(data.message);
                }
            } catch (error) {
                console.error('Error adding product:', error);
                if (typeof notyf !== 'undefined') notyf.error('An unexpected error occurred.');
            } finally {
                modalLoading.style.display = 'none';
            }
        }

        // Modal Search
        document.getElementById('modalProductSearch').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            document.querySelectorAll('.modal-product-btn').forEach(btn => {
                const name = btn.dataset.name;
                btn.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });

        // Status Polling
        function startStatusPolling() {
            setInterval(async () => {
                try {
                    const response = await fetch("{{ route('customers.orders.get_status') }}");
                    const data = await response.json();
                    if (data.success) {
                        Object.entries(data.statuses).forEach(([orderNumber, status]) => {
                            const badge = document.getElementById(`status-badge-${orderNumber}`);
                            if (badge) {
                                const currentStatus = badge.textContent.trim().toLowerCase();
                                const newStatus = status.toLowerCase();

                                if (currentStatus !== newStatus && !(currentStatus ===
                                        'pending confirmation' && newStatus === 'pending')) {
                                    // Update Badge
                                    let statusClass = 'status-pending';
                                    let statusLabel = status;

                                    switch (newStatus) {
                                        case 'pending':
                                            statusClass = 'status-pending';
                                            statusLabel = 'Pending Confirmation';
                                            break;
                                        case 'preparing':
                                            statusClass = 'status-preparing';
                                            statusLabel = 'Preparing';
                                            break;
                                        case 'ready':
                                        case 'ready for pickup':
                                            statusClass = 'status-ready';
                                            statusLabel = 'Ready for pickup';
                                            break;
                                        case 'completed':
                                            statusClass = 'status-completed';
                                            statusLabel = 'Completed';
                                            break;
                                        case 'cancelled':
                                            statusClass = 'status-cancelled';
                                            statusLabel = 'Cancelled';
                                            break;
                                    }

                                    badge.className = `status-badge ${statusClass}`;
                                    badge.textContent = statusLabel;

                                    // Handle UI changes based on status
                                    if (newStatus !== 'pending') {
                                        // Hide "Add" button
                                        const addBtn = document.getElementById(
                                        `btn-add-${orderNumber}`);
                                        if (addBtn) addBtn.style.display = 'none';

                                        // Convert quantity buttons to static text
                                        document.querySelectorAll(
                                            `#items-list-${orderNumber} .item-qty-wrap`).forEach(
                                            wrap => {
                                                const qty = wrap.querySelector('span').textContent;
                                                const staticDiv = document.createElement('div');
                                                staticDiv.className =
                                                    'd-flex align-items-center gap-2';
                                                staticDiv.innerHTML =
                                                    `<span class="text-muted fw-bold" style="font-size: 0.85rem;">${qty}x</span>`;
                                                wrap.replaceWith(staticDiv);
                                            });
                                    }

                                    if (newStatus === 'completed') {
                                        const chatInput = document.getElementById(
                                            `chat-input-group-${orderNumber}`);
                                        if (chatInput) {
                                            const disabledMsg = document.createElement('div');
                                            disabledMsg.id = `chat-disabled-${orderNumber}`;
                                            disabledMsg.className = 'text-center text-muted small py-2';
                                            disabledMsg.textContent =
                                                'Chat is disabled for completed orders.';
                                            chatInput.replaceWith(disabledMsg);
                                        }
                                    }
                                }
                            }
                        });
                    }
                } catch (e) {
                    console.error('Status polling error:', e);
                }
            }, 10000); // Poll every 10 seconds
        }

        startStatusPolling();

        // Allow 'Enter' key to send message
        document.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && e.target.classList.contains('chat-input')) {
                const orderNumber = e.target.id.replace('input-', '');
                const btn = document.querySelector(`.btn-send-chat[data-order-number="${orderNumber}"]`);
                if (btn) btn.click();
            }
        });
    </script>
</body>

</html>
