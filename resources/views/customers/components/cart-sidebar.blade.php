<div class="cart-overlay" id="cartOverlay"></div>

<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h5 class="cart-title">Your order</h5>
        <button class="btn-close-cart" id="closeCart">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="cart-body" id="cartItemsContainer">
        <!-- Cart items will be loaded here -->
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="cart-footer">
        <div class="cart-note-wrap mb-3">
            <label for="orderNote" class="form-label small fw-bold text-muted mb-1">Add a note (optional)</label>
            <textarea class="form-control form-control-sm" id="orderNote" rows="2" placeholder="Special instructions..."></textarea>
        </div>
        <div class="cart-total-wrap">
            <span class="cart-total-label">Total</span>
            <span class="cart-total-value" id="cartTotal">₱0.00</span>
        </div>
        <button class="btn-checkout">Checkout</button>
    </div>
</div>
