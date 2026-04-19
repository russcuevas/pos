<div class="modal fade" id="viewProductModal{{ $product->id }}" tabindex="-1"
    aria-labelledby="viewProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProductModalLabel{{ $product->id }}">View Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6 border-end">
                        <div class="mb-3 d-flex flex-column align-items-center">
                            <div class="form-label">
                                <div
                                    style="width: 150px; height: 150px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; background-size: cover; background-position: center; border-radius: 8px; background-image: url('{{ $product->product_image ? asset('images/products/' . $product->product_image) : '' }}');">
                                    @if (!$product->product_image)
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted mb-0 small">Barcode / Product Code</label>
                            <div class="fw-bold fs-6">{{ $product->product_code }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted mb-0 small">Product Name</label>
                            <div class="fw-bold fs-6">{{ $product->product_name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted mb-0 small">Description</label>
                            <div class="fw-bold">{{ $product->product_description ?: 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6 ps-md-4">
                        <div class="mb-4 pt-2">
                            <label class="form-label text-muted mb-1 d-block small">Visibility</label>
                            @if ($product->is_show)
                                <span class="badge bg-success fs-6"><i class="bi bi-eye"></i> Shown to Customers</span>
                            @else
                                <span class="badge bg-secondary fs-6"><i class="bi bi-eye-slash"></i> Hidden</span>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-0 small">Selling Price</label>
                                <div class="fw-bold fs-5 text-primary">₱ {{ number_format($product->selling_price, 2) }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-0 small">Supplier Price</label>
                                <div class="fw-bold fs-5">₱ {{ number_format($product->supplier_price, 2) }}</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted mb-0 small">Available Quantity</label>
                            <div class="fw-bold fs-5">{{ $product->quantity ?? 0 }}</div>
                        </div>

                        <h6 class="text-muted border-bottom pb-2 mb-3">WHOLE SALE INFORMATION</h6>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-0 small">Wholesale Price</label>
                                <div class="fw-bold fs-5 text-success">
                                    ₱{{ number_format($product->whole_sale_price, 2) }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-0 small">Minimum Quantity</label>
                                <div class="fw-bold fs-5">{{ $product->whole_sale_qty }}</div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
