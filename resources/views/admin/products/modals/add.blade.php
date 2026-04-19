<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6 border-end">
                            <div class="mb-3 d-flex flex-column align-items-center">
                                <label for="product_image" class="form-label" style="cursor: pointer;">
                                    <div id="imagePreview"
                                        style="width: 150px; height: 150px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; background-size: cover; background-position: center; border-radius: 8px;">
                                        <i class="bi bi-plus-lg fs-1 text-muted" id="plusIcon"></i>
                                    </div>
                                </label>
                                <input type="file" id="product_image" name="product_image" accept="image/*"
                                    class="d-none" required onchange="previewImage(this, 'imagePreview', 'plusIcon')">
                                <div class="mt-2 text-muted small fw-bold">Upload Product Image</div>
                            </div>

                            <div class="mb-3">
                                <label for="product_code" class="form-label">Barcode / Product Code</label>
                                <input type="text" class="form-control" id="product_code" name="product_code"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="product_description" class="form-label">Description</label>
                                <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6 ps-md-4">
                            <div class="mb-4 form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_show"
                                    name="is_show" value="1" checked>
                                <label class="form-check-label fw-bold text-primary" for="is_show">Show to Customer
                                    View</label>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="selling_price" class="form-label">Selling Price</label>
                                    <input type="number" class="form-control" id="selling_price" name="selling_price"
                                        step="0.01" min="0" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="supplier_price" class="form-label">Supplier Price</label>
                                    <input type="number" class="form-control" id="supplier_price" name="supplier_price"
                                        step="0.01" min="0" required>
                                </div>
                            </div>

                            <h5>WHOLE SALE PRICE</h5>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="whole_sale_qty" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="whole_sale_qty" name="whole_sale_qty"
                                        step="0.01" min="0" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="whole_sale_price" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="whole_sale_price"
                                        name="whole_sale_price" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
