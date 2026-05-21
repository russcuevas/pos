<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content"
            style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title w-100 text-center fw-bold" id="checkoutModalLabel"
                    style="color: var(--text-primary);">Checkout</h5>
                <button type="button" class="btn-close d-none" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <form id="checkoutForm" method="POST" action="{{ route('admin.pos.cart.checkout') }}">
                    @csrf
                    <!-- Customer Details -->
                    <div class="mb-3 text-center">
                        <button type="button" class="btn btn-link text-decoration-none p-0 mb-2"
                            data-bs-toggle="collapse" data-bs-target="#customerDetailsCollapse">
                            <i class="bi bi-person-plus"></i> Add Customer
                        </button>
                        <div class="collapse" id="customerDetailsCollapse">
                            <input type="text" name="customer_name" class="form-control mb-2"
                                placeholder="Customer Name (Required)" required>
                            <input type="text" name="customer_phone" class="form-control mb-2"
                                placeholder="Phone (Optional)">
                            <textarea name="address" class="form-control" placeholder="Address (Optional)" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="mb-3 text-center">
                        <button type="button" class="btn btn-link text-decoration-none p-0"
                            data-bs-toggle="collapse" data-bs-target="#discountCollapse">
                            <i class="bi bi-tag"></i> Add Discount
                        </button>
                        <div class="collapse" id="discountCollapse">
                            <input type="number" name="discount_price" class="form-control mt-2"
                                placeholder="Discount Amount" min="0" step="0.01">
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="text-center mb-4">
                        <div style="color: var(--text-secondary); font-size: 0.9em;">Total Amount Due</div>
                        <div class="d-flex flex-column align-items-center justify-content-center mt-1">
                            <span id="originalTotalDisplay" class="text-muted text-decoration-line-through d-none" style="font-size: 1.1rem; margin-bottom: -5px;">
                                ₱{{ number_format($subtotal, 2) }}
                            </span>
                            <h2 class="fw-bold text-primary mb-0" id="checkoutTotalDisplay">
                                ₱{{ number_format($subtotal, 2) }}
                            </h2>
                        </div>
                        <div id="discountAmountDisplay" class="text-danger small fw-bold d-none mt-1"></div>
                        <input type="hidden" name="total_price" id="checkoutTotalInput"
                            value="{{ $subtotal }}">
                    </div>

                    <!-- Payment Method Tabs -->
                    <ul class="nav nav-tabs w-100 mb-3" id="paymentMethodTabs" role="tablist">
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link active w-100 fw-bold" id="cash-tab" data-bs-toggle="tab"
                                data-bs-target="#cash-pane" type="button" role="tab">Cash</button>
                        </li>
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link w-100 fw-bold" id="ecash-tab" data-bs-toggle="tab"
                                data-bs-target="#ecash-pane" type="button" role="tab">E-Cash</button>
                        </li>
                    </ul>
                    <input type="hidden" name="payment_method" id="paymentMethodInput" value="Cash">

                    <!-- Enter Payment Amount -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85em; color: var(--text-secondary);">Enter
                            Payment Amount</label>
                        <input type="number" name="payment_amount" id="paymentAmountInput"
                            class="form-control text-center fs-4 fw-bold mb-2" required min="0"
                            step="0.01" style="height: 60px;">

                        <div class="d-flex justify-content-between gap-1 mt-2">
                            <button type="button" class="btn btn-light border flex-fill fw-bold quick-pay-btn"
                                data-val="50">₱50</button>
                            <button type="button" class="btn btn-light border flex-fill fw-bold quick-pay-btn"
                                data-val="100">₱100</button>
                            <button type="button" class="btn btn-light border flex-fill fw-bold quick-pay-btn"
                                data-val="200">₱200</button>
                            <button type="button" class="btn btn-light border flex-fill fw-bold quick-pay-btn"
                                data-val="500">₱500</button>
                            <button type="button" class="btn btn-light border flex-fill fw-bold quick-pay-btn"
                                data-val="1000">₱1,000</button>
                        </div>
                    </div>

                    <!-- Change Display -->
                    <div class="d-flex justify-content-between mb-4 px-2" style="font-size: 1.1em;">
                        <span class="text-secondary fw-bold">Change:</span>
                        <span class="fw-bold text-success" id="changeDisplay">₱0.00</span>
                    </div>

                    <!-- Submit -->
                    <button type="button" id="confirmPaymentBtn" class="btn w-100 py-3 fw-bold"
                        style="background-color: #198754; color: #fff; border-radius: 8px;">Confirm
                        Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script>
        (function(){
            function handleCheckoutSubmit(form){
                console.log('checkout: submit intercepted');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;

                const url = form.action;
                const fd = new FormData(form);

                // Ask user first whether they want to print after payment
                Swal.fire({
                    title: 'Confirm Payment',
                    text: 'Do you want to print the receipt after payment?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, print',
                    cancelButtonText: 'No'
                }).then(choice => {
                    const wantsPrint = !!choice.isConfirmed;

                    let printWindowReady = null;
                    const printReceipt = (receiptText) => {
                        let iframe = document.getElementById('receiptPrintIframe');
                        if (!iframe) {
                            iframe = document.createElement('iframe');
                            iframe.id = 'receiptPrintIframe';
                            iframe.style.position = 'fixed';
                            iframe.style.width = '0';
                            iframe.style.height = '0';
                            iframe.style.border = '0';
                            iframe.style.visibility = 'hidden';
                            iframe.style.left = '-9999px';
                            document.body.appendChild(iframe);
                        }

                        printWindowReady = iframe;
                        iframe.onload = function() {
                            try {
                                iframe.contentWindow.focus();
                                iframe.contentWindow.print();
                            } catch (err) {
                                console.warn('Print failed on iframe', err);
                                alert('Unable to print receipt automatically.');
                            }
                            setTimeout(() => window.location.reload(), 1000);
                        };

                        const doc = iframe.contentDocument || iframe.contentWindow.document;
                        doc.open();
                        doc.write('<!DOCTYPE html><html><head><title>Receipt</title><style>body{margin:0;padding:6px;font-family:Courier New,monospace;white-space:pre-wrap;}</style></head><body><pre>' + receiptText.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre></body></html>');
                        doc.close();
                    };

                    // Proceed with payment request
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: fd
                    })
                    .then(response => {
                        const ct = response.headers.get('content-type') || '';
                        if (ct.includes('application/json')) return response.json();
                        return response.text().then(text => {
                            try { return JSON.parse(text); }
                            catch (err) { return { success: true, redirect: response.url }; }
                        });
                    })
                    .then(data => {
                        console.log('checkout: response', data);
                        if (data.success) {
                            if (wantsPrint) {
                                const orderNumber = data.order_number || (data.order && data.order.order_number) || (data.redirect ? data.redirect.split('/').pop() : null);
                                const receiptUrl = "{{ url('/admin/orders/receipt') }}/" + encodeURIComponent(orderNumber || '');
                                fetch(receiptUrl)
                                    .then(r => r.text())
                                    .then(printReceipt)
                                    .catch(err => {
                                        console.error('receipt fetch error', err);
                                        alert('Unable to fetch receipt for printing.');
                                        window.location.reload();
                                    });
                            } else {
                                window.location.reload();
                            }
                        } else {
                            console.warn('checkout: server returned failure', data);
                            alert(data.message || 'Payment failed');
                            if (submitBtn) submitBtn.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error('checkout: error', err);
                        alert('An unexpected error occurred.');
                        if (submitBtn) submitBtn.disabled = false;
                    });
                });
            }

            // Capture submit events for the checkout form even if submitted by other scripts
            document.addEventListener('submit', function(e){
                const form = e.target;
                if (!form || form.id !== 'checkoutForm') return;
                e.preventDefault();
                handleCheckoutSubmit(form);
            }, true);

            // Also attach directly in case the element is submitted programmatically
            const directForm = document.getElementById('checkoutForm');
            if (directForm) directForm.addEventListener('submit', function(e){ e.preventDefault(); handleCheckoutSubmit(this); });

            // Attach click listener to the confirm button to prevent default form submission
            const confirmBtn = document.getElementById('confirmPaymentBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    const form = document.getElementById('checkoutForm');
                    if (form) handleCheckoutSubmit(form);
                });
            }
        })();
    </script>
