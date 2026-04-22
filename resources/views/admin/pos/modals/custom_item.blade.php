<!-- Custom Item Modal -->
<div class="modal fade" id="customItemModal" tabindex="-1" aria-labelledby="customItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content"
            style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title w-100 fw-bold" id="customItemModalLabel"
                    style="color: var(--text-primary);">Add Custom Item / Service</h5>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <form method="POST" action="{{ route('admin.pos.cart.custom') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size: 0.85em; color: var(--text-secondary); font-weight: 500;">Name</label>
                        <input type="text" name="custom_entry" class="form-control"
                            placeholder="e.g., Delivery Fee" required style="border-width: 2px;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label"
                            style="font-size: 0.85em; color: var(--text-secondary); font-weight: 500;">Price
                            (₱)</label>
                        <input type="number" name="custom_price" class="form-control" placeholder="0.00"
                            min="0" step="0.01" required style="border-width: 2px;">
                    </div>
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="font-weight: 600; background-color: #e2e8f0; color: #475569;">Cancel</button>
                        <button type="submit" class="btn btn-success px-4"
                            style="background-color: #22c55e; border: none; font-weight: 600;">Add Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
