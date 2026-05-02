@php $isCancelled = strtolower($order->status) === 'cancelled'; @endphp
<div class="order-card {{ $isCancelled ? 'cancelled-order' : '' }}" id="order-card-{{ $order->order_number }}">
    <div class="order-card-header">
        <div>
            <div class="customer-info" style="font-size: 0.9rem; color: #718096; font-weight: normal;">
                {{ $order->customer_name ?: 'No name' }}
            </div>
            <div class="order-number" style="font-size: 1.1rem; font-weight: 800;">Order {{ $order->order_number }}</div>
            <div class="order-date" style="color: #a0aec0; font-size: 0.8rem;">
                {{ $order->updated_at->format('n/j/Y, g:i:s A') }}</div>
        </div>
        <div class="text-end">
            @if ($order->discount_price > 0)
                <div class="original-total"
                    style="text-decoration: line-through; color: #a0aec0; font-size: 0.85rem; font-weight: 600; margin-bottom: -4px;">
                    Original: ₱{{ number_format($order->original_total, 2) }}
                </div>
                <span style="color: #ef4444; font-size: 0.75rem; font-weight: 600;">
                    -₱{{ number_format($order->discount_price, 2) }} Discount
                </span>
            @endif
            <div class="order-total"
                style="font-size: 1.3rem; font-weight: 800; color: {{ $isCancelled ? '#94a3b8' : '#0284c7' }};">
                ₱{{ number_format($order->total_amount, 2) }}
            </div>
            @if (!$isCancelled)
                <div class="profit-text profit-info" style="color: #10b981; font-size: 0.85rem; font-weight: 700;">
                    Profit: ₱{{ number_format($order->total_profit, 2) }}
                </div>
            @endif
            <div class="status-badge mt-2"
                style="background: {{ $isCancelled ? '#ef4444' : '#10b981' }}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                {{ ucfirst($order->status) }}
            </div>
        </div>
    </div>

    <div class="order-card-body">
        <div class="order-items-list">
            @foreach ($order->items as $item)
                <div class="order-item-row" style="border-top: 1px solid #f1f5f9; padding: 10px 0;">
                    <div class="item-info">
                        <span class="item-name" style="font-weight: 600; color: #2d3748;">
                            {{ (int) $item->quantity }}x {{ $item->product->product_name ?? 'Product' }}
                        </span>
                    </div>
                    <div class="text-end">
                        <div class="item-price" style="font-weight: 700; color: #2d3748;">
                            ₱{{ number_format($item->total_price, 2) }}
                        </div>
                        @if (!$isCancelled)
                            <div class="item-profit profit-info"
                                style="color: #10b981; font-size: 0.75rem; font-weight: 600;">
                                Profit: ₱{{ number_format($item->profit, 2) }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if (!$isCancelled)
        <div class="order-card-footer">
            <div class="d-flex gap-3">
                <a href="javascript:void(0)" class="footer-link"
                    style="color: #ef4444; text-decoration: none; font-weight: 600; font-size: 0.85rem;">
                    <i class="bi bi-arrow-counterclockwise"></i> Return
                </a>
                <a href="javascript:void(0)" class="footer-link btn-print-receipt"
                    data-order-json="{{ json_encode($order) }}"
                    style="color: #0284c7; text-decoration: none; font-weight: 600; font-size: 0.85rem;">
                    <i class="bi bi-printer"></i> Print
                </a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label class="form-check-label" style="font-size: 0.8rem;"
                    for="exclude-{{ $order->order_number }}">Exclude
                    Sales</label>
                <input class="form-check-input exclude-sales-toggle" type="checkbox"
                    id="exclude-{{ $order->order_number }}" data-order="{{ $order->order_number }}">
            </div>
        </div>
    @endif
</div>
