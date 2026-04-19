<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1"
    aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6 border-end">
                            <div class="mb-3 d-flex flex-column align-items-center">
                                <label for="product_image_{{ $product->id }}" class="form-label"
                                    style="cursor: pointer;">
                                    <div id="imagePreview_{{ $product->id }}"
                                        style="width: 150px; height: 150px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; background-size: cover; background-position: center; border-radius: 8px; background-image: url('{{ $product->product_image ? asset('images/products/' . $product->product_image) : '' }}');">
                                        <i class="bi bi-plus-lg fs-1 text-muted" id="plusIcon_{{ $product->id }}"
                                            style="{{ $product->product_image ? 'display: none;' : '' }}"></i>
                                    </div>
                                </label>
                                <input type="file" id="product_image_{{ $product->id }}" name="product_image"
                                    accept="image/*" class="d-none"
                                    onchange="previewImage(this, 'imagePreview_{{ $product->id }}', 'plusIcon_{{ $product->id }}')">
                                <div class="mt-2 text-muted small fw-bold">Update Product Image</div>
                            </div>

                            <div class="mb-3">
                                <label for="product_code_{{ $product->id }}" class="form-label">Barcode / Product
                                    Code</label>
                                <input type="text" class="form-control" id="product_code_{{ $product->id }}"
                                    name="product_code" value="{{ $product->product_code }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="product_name_{{ $product->id }}" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name_{{ $product->id }}"
                                    name="product_name" value="{{ $product->product_name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="product_description_{{ $product->id }}"
                                    class="form-label">Description</label>
                                <textarea class="form-control" id="product_description_{{ $product->id }}" name="product_description" rows="3"
                                    required>{{ $product->product_description }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6 ps-md-4">
                            <div class="mb-4 form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="is_show_{{ $product->id }}" name="is_show" value="1"
                                    {{ $product->is_show ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-primary"
                                    for="is_show_{{ $product->id }}">Show to Customer View</label>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="selling_price_{{ $product->id }}" class="form-label">Selling
                                        Price</label>
                                    <input type="number" class="form-control" id="selling_price_{{ $product->id }}"
                                        name="selling_price" step="0.01" min="0"
                                        value="{{ $product->selling_price }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="supplier_price_{{ $product->id }}" class="form-label">Supplier
                                        Price</label>
                                    <input type="number" class="form-control" id="supplier_price_{{ $product->id }}"
                                        name="supplier_price" step="0.01" min="0"
                                        value="{{ $product->supplier_price }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="whole_sale_price_{{ $product->id }}" class="form-label">Wholesale
                                        Price</label>
                                    <input type="number" class="form-control"
                                        id="whole_sale_price_{{ $product->id }}" name="whole_sale_price"
                                        step="0.01" min="0" value="{{ $product->whole_sale_price }}"
                                        required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="whole_sale_qty_{{ $product->id }}" class="form-label">Minimum
                                        Quantity</label>
                                    <input type="number" class="form-control"
                                        id="whole_sale_qty_{{ $product->id }}" name="whole_sale_qty" step="1"
                                        min="0" value="{{ $product->whole_sale_qty }}" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
