<div class="order-card" id="order-card-{{ $order->order_number }}">
    <div class="order-card-header">
        <div>
            <div class="order-number">Order {{ $order->order_number }}</div>
            <div class="order-date">{{ $order->created_at->format('n/j/Y, g:i:s A') }}</div>
            <div class="customer-info">
                {{ $order->customer_name }} (<span class="text-primary">{{ $order->customer_phone }}</span>)
            </div>
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
                    $statusLabel = 'Pending';
                    break;
                case 'preparing':
                    $statusClass = 'status-preparing';
                    $statusLabel = 'Preparing';
                    break;
                case 'ready':
                case 'ready for pickup':
                    $statusClass = 'status-ready';
                    $statusLabel = 'Ready for pickup';
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
                        <div class="item-qty-wrap">
                            <button class="btn-qty-subtle btn-order-qty-adj" data-order-id="{{ $item->id }}"
                                data-action="decrement">
                                <i class="bi bi-dash"></i>
                            </button>
                            <span class="fw-bold" id="item-qty-{{ $item->id }}"
                                style="min-width: 15px; text-align: center;">{{ (int) $item->quantity }}</span>
                            <button class="btn-qty-subtle btn-order-qty-adj" data-order-id="{{ $item->id }}"
                                data-action="increment">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <span class="item-name">{{ $item->product->product_name ?? 'Product' }}</span>
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
                                $wholesaleBundles = (int) floor($item->quantity / $product->whole_sale_qty);
                                $regularItems = (int) ($item->quantity % $product->whole_sale_qty);
                            }
                        @endphp
                        <div id="wholesale-wrap-{{ $item->id }}"
                            style="display: {{ $wholesaleBundles > 0 ? 'block' : 'none' }};">
                            <span class="wholesale-badge">Wholesale Applied</span>
                        </div>
                        <div class="item-price" id="item-price-{{ $item->id }}">
                            ₱{{ number_format($item->total_price, 2) }}</div>
                        <small class="text-muted wholesale-details" id="wholesale-details-{{ $item->id }}"
                            style="display: {{ $wholesaleBundles > 0 ? 'block' : 'none' }};">
                            <span id="bundles-{{ $item->id }}">{{ $wholesaleBundles }}</span>x
                            (Whole)
                            <span id="regular-wrap-{{ $item->id }}"
                                style="display: {{ $regularItems > 0 ? 'inline' : 'none' }};">
                                <br><span id="regular-{{ $item->id }}">{{ $regularItems }}</span>x
                                (Retail)
                            </span>
                        </small>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($order->note)
            <div class="order-note-section">
                <strong>Customer Note:</strong> {{ $order->note }}
            </div>
        @endif
    </div>

    <div class="order-card-footer">
        <div class="d-flex align-items-center gap-3">
            <a href="javascript:void(0)" class="footer-link btn-toggle-chat"
                data-order-number="{{ $order->order_number }}">
                <i class="bi bi-chat-dots-fill"></i>
                Chat <span class="badge bg-secondary rounded-pill ms-1" style="font-size: 0.6rem; padding: 2px 5px;"
                    id="chat-count-{{ $order->order_number }}">{{ $order->chats_count }}</span>
            </a>
        </div>

        <div class="footer-actions-group d-flex align-items-center gap-2">
            <a href="javascript:void(0)" class="footer-link footer-link-add btn-open-product-modal"
                data-order-number="{{ $order->order_number }}">
                <i class="bi bi-plus-circle-fill"></i>
                Add
            </a>
            <a href="javascript:void(0)" class="footer-link footer-link-cancel btn-cancel-order"
                data-order-number="{{ $order->order_number }}">
                <i class="bi bi-x-circle-fill"></i>
                Cancel
            </a>
            @if (strtolower($order->status) === 'pending')
                <button class="btn-start-preparing btn-action-start" data-order-number="{{ $order->order_number }}">
                    <i class="bi bi-play-fill"></i>
                    Start Preparing
                </button>
            @endif
            @if (strtolower($order->status) === 'preparing')
                <button class="btn-start-preparing btn-action-ready" data-order-number="{{ $order->order_number }}"
                    style="background: #10b981;">
                    <i class="bi bi-check-circle-fill"></i>
                    Mark as Ready
                </button>
            @endif
            @if (strtolower($order->status) === 'ready' || strtolower($order->status) === 'ready for pickup')
                <button class="btn-start-preparing btn-action-checkout" data-order-number="{{ $order->order_number }}"
                    data-total="{{ $order->total_amount }}" data-customer-name="{{ $order->customer_name }}"
                    style="background: #0284c7;">
                    <i class="bi bi-cart-check-fill"></i>
                    Checkout
                </button>
            @endif
        </div>
    </div>

    <!-- Chat Section -->
    <div class="chat-section" id="chat-{{ $order->order_number }}">
        <div class="chat-messages-container" id="messages-container-{{ $order->order_number }}">
            @foreach ($order->chats as $chat)
                @php
                    $isCustomer = !($chat->customer_id === null && $chat->allowed !== null);
                @endphp
                <div class="chat-bubble {{ $isCustomer ? 'chat-bubble-customer' : 'chat-bubble-admin' }}">
                    <div class="chat-text">{{ $chat->message }}</div>
                    <span class="chat-time">{{ $chat->created_at->format('g:i:s A') }}</span>
                </div>
            @endforeach
        </div>
        <div class="chat-input-group">
            <input type="text" class="chat-input" placeholder="Type a message..."
                id="input-{{ $order->order_number }}">
            <button class="btn-send-chat" data-order-number="{{ $order->order_number }}">Send</button>
        </div>
    </div>
</div>
