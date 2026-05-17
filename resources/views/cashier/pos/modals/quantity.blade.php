<!-- Set Quantity Modal -->
<div class="modal fade" id="qtyModal" tabindex="-1" aria-labelledby="qtyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 320px;">
        <div class="modal-content"
            style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title w-100 text-center fw-bold" id="qtyModalLabel" style="color: #1e293b;">Set
                    Quantity</h5>
                <button type="button" class="btn-close d-none" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <form id="qtySetForm" method="POST" action="">
                    @csrf
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                        <button type="button" class="btn qty-quick-btn" data-val="0.25">1/4</button>
                        <button type="button" class="btn qty-quick-btn" data-val="0.33">1/3</button>
                        <button type="button" class="btn qty-quick-btn" data-val="0.5">1/2</button>
                        <button type="button" class="btn qty-quick-btn" data-val="0.75">3/4</button>
                        <button type="button" class="btn qty-quick-btn" data-val="1">1</button>
                        <button type="button" class="btn qty-quick-btn" data-val="2">2</button>
                        <button type="button" class="btn qty-quick-btn" data-val="3">3</button>
                        <button type="button" class="btn qty-quick-btn" data-val="4">4</button>
                        <button type="button" class="btn qty-quick-btn" data-val="5">5</button>
                        <button type="button" class="btn qty-quick-btn" data-val="10">10</button>
                        <button type="button" class="btn qty-quick-btn" data-val="15">15</button>
                        <button type="button" class="btn qty-quick-btn" data-val="20">20</button>
                    </div>

                    <div class="mb-1">
                        <label class="form-label"
                            style="font-size: 0.85em; color: #475569; font-weight: 500;">Custom Quantity</label>
                        <div class="input-group">
                            <input type="number" name="quantity" id="customQtyInput" class="form-control"
                                min="0.01" step="0.01" required
                                style="border-color: #cbd5e1; border-width: 2px;">
                            <button type="submit" class="btn btn-success px-4"
                                style="background-color: #22c55e; border: none; font-weight: 600;">Set</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
