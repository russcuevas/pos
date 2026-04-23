<form id="saveOrderForm" action="{{ route('admin.pos.cart.save') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="modal fade" id="savedOrdersModal" tabindex="-1" aria-labelledby="savedOrdersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="savedOrdersModalLabel"><i class="bi bi-folder2-open me-2"></i>Saved
                    Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                @if (isset($savedOrders) && $savedOrders->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">No saved orders yet</h5>
                    </div>
                @elseif(isset($savedOrders))
                    <div class="accordion accordion-flush" id="savedOrdersAccordion">
                        @foreach ($savedOrders as $reference => $items)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $reference }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $reference }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $reference }}">
                                        <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                            <div>
                                                <div class="fw-bold">{{ $reference }}</div>
                                                <div class="small text-muted">
                                                    {{ $items->first()->created_at->format('M d, Y h:i A') }}</div>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $items->count() }}
                                                item(s)</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $reference }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading-{{ $reference }}"
                                    data-bs-parent="#savedOrdersAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Item</th>
                                                        <th class="text-end">Price</th>
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-end">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $orderTotal = 0; @endphp
                                                    @foreach ($items as $item)
                                                        @php
                                                            $price = $item->product_id
                                                                ? $item->selling_price
                                                                : $item->custom_price;
                                                            $name = $item->product_id
                                                                ? $item->product_name
                                                                : $item->custom_entry;
                                                            $subtotal = $price * $item->quantity;
                                                            $orderTotal += $subtotal;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    @if ($item->product_id && $item->product_image)
                                                                        <img src="{{ asset('images/products/' . $item->product_image) }}"
                                                                            alt=""
                                                                            style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;">
                                                                    @else
                                                                        <div
                                                                            style="width: 32px; height: 32px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                                            📦</div>
                                                                    @endif
                                                                    <span>{{ $name }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-end">₱{{ number_format($price, 2) }}
                                                            </td>
                                                            <td class="text-center">{{ $item->quantity + 0 }}</td>
                                                            <td class="text-end">
                                                                ₱{{ number_format($subtotal, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-end fw-bold">Order Total:
                                                        </td>
                                                        <td class="text-end fw-bold text-primary">
                                                            ₱{{ number_format($orderTotal, 2) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <form action="{{ route('admin.pos.saved.delete', $reference) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this saved order?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.pos.saved.load', $reference) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-cart-plus"></i> Load to Cart
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
