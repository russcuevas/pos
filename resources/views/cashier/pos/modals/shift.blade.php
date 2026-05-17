<style>
    /* Light Mode (Default) */
    .shift-modal-content {
        background-color: #f4f7fb;
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .shift-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .shift-text-main {
        color: #212529;
    }

    .shift-text-muted {
        color: #6c757d;
    }

    .shift-btn-standard {
        background-color: #ffffff;
        color: #1e293b;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: transform 0.1s;
    }

    .shift-btn-exit {
        background-color: #ffffff;
        color: #64748b;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: transform 0.1s;
    }

    .shift-btn-details {
        background-color: #e0f2fe;
        color: #0284c7;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .shift-pull-bar {
        background-color: #94a3b8;
    }

    /* Dark Mode overrides */
    .dark-mode .shift-modal-content {
        background-color: var(--pos-bg);
        border: 1px solid var(--pos-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .dark-mode .shift-card {
        background-color: var(--pos-bg-alt);
        border: 1px solid var(--pos-border);
        box-shadow: none;
    }

    .dark-mode .shift-text-main {
        color: var(--text-primary) !important;
    }

    .dark-mode .shift-text-muted {
        color: var(--text-secondary) !important;
    }

    .dark-mode .shift-btn-standard {
        background-color: var(--pos-bg-alt);
        color: var(--text-primary);
        border: 1px solid var(--pos-border);
        box-shadow: none;
    }

    .dark-mode .shift-btn-exit {
        background-color: var(--pos-bg-alt);
        color: var(--text-secondary);
        border: 1px solid var(--pos-border);
        box-shadow: none;
    }

    .dark-mode .shift-btn-details {
        background-color: rgba(2, 132, 199, 0.2);
        color: #38bdf8;
    }

    .dark-mode .shift-pull-bar {
        background-color: var(--pos-border);
    }
</style>

<!-- Shift Modal -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shift-modal-content">
            <div class="modal-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="shift-pull-bar"
                        style="width: 40px; height: 4px; border-radius: 2px; margin: 0 auto 15px auto;"></div>
                    <h4 class="fw-bold shift-text-main mb-1">Good afternoon,
                        {{ Auth::guard('cashier')->user()->fullname ?? 'Sam' }}!</h4>
                    <div class="shift-text-muted" style="font-size: 0.9rem;">
                        <i class="bi bi-calendar3 me-1"></i> <span id="realtime-clock">{{ \Carbon\Carbon::now()->format('l, F d, Y - h:i A') }}</span>
                    </div>
                </div>

                @if (!$pettyCash)
                    <form action="{{ route('cashier.pos.start_shift') }}" method="POST">
                        @csrf
                        <div class="text-center mb-4">
                            <p class="shift-text-muted">Please enter your starting petty cash for today before
                                proceeding to the POS.</p>
                        </div>

                        <div class="shift-card p-4 mb-4 text-center">
                            <label class="shift-text-muted fw-bold mb-3 d-block" style="font-size: 0.9rem;">PETTY
                                CASH</label>
                            <div class="input-group input-group-lg mx-auto" style="max-width: 300px;">
                                <span class="input-group-text bg-light border-end-0 fw-bold">₱</span>
                                <input type="number" name="beginning_balance"
                                    class="form-control border-start-0 fw-bold text-center" placeholder="0.00" required
                                    min="0" step="0.01" style="font-size: 1.5rem;" autofocus>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <button type="submit"
                                    class="btn w-100 py-3 rounded-3 fw-bold d-flex flex-column align-items-center justify-content-center h-100"
                                    style="background-color: #22c55e; color: white; border: none; box-shadow: 0 4px 6px rgba(34, 197, 94, 0.2); transition: transform 0.1s;"
                                    onmousedown="this.style.transform='scale(0.98)'"
                                    onmouseup="this.style.transform='scale(1)'">
                                    <i class="bi bi-play-circle-fill mb-1" style="font-size: 1.4rem;"></i>
                                    Start Shift
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" onclick="document.getElementById('logoutForm').submit();"
                                    class="btn w-100 py-3 rounded-3 fw-bold d-flex flex-column align-items-center justify-content-center h-100 shift-btn-exit"
                                    onmousedown="this.style.transform='scale(0.98)'"
                                    onmouseup="this.style.transform='scale(1)'">
                                    <i class="bi bi-door-open-fill mb-1" style="color: #ef4444; font-size: 1.4rem;"></i>
                                    Exit
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="logoutForm" action="{{ route('cashier.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <!-- Sales Today -->
                    <div class="shift-card p-3 px-4 mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="shift-text-muted mb-1" style="font-size: 0.8rem; font-weight: 600;">Sales Today
                            </div>
                            <div class="fw-bold shift-text-main" style="font-size: 1.5rem;">
                                ₱{{ number_format($salesToday ?? 0, 2) }}</div>
                        </div>
                        <button type="button" class="btn btn-sm fw-bold px-3 py-1 shift-btn-details"
                            data-bs-toggle="modal" data-bs-target="#salesDetailsModal" data-bs-dismiss="modal">Details</button>
                    </div>

                    <!-- Shift Duration -->
                    <div class="shift-card p-3 mb-3 text-center">
                        <div class="shift-text-muted mb-1" style="font-size: 0.8rem; font-weight: 600;">Shift Duration
                        </div>
                        <div class="fw-bold shift-text-main" id="shiftDurationDisplay"
                            style="font-size: 1.75rem; letter-spacing: 2px;">00:00:00</div>
                    </div>

                    <div class="row g-3">
                        <!-- Continue Shift -->
                        <div class="col-md-6">
                            <button
                                class="btn w-100 py-3 rounded-3 fw-bold d-flex flex-column align-items-center justify-content-center h-100"
                                style="background-color: #22c55e; color: white; border: none; box-shadow: 0 4px 6px rgba(34, 197, 94, 0.2); transition: transform 0.1s;"
                                data-bs-dismiss="modal" onmousedown="this.style.transform='scale(0.98)'"
                                onmouseup="this.style.transform='scale(1)'">
                                <i class="bi bi-play-circle-fill mb-1" style="font-size: 1.4rem;"></i>
                                Continue Shift
                            </button>
                        </div>

                        <!-- Petty Cash -->
                        <div class="col-md-6">
                            <button type="button"
                                data-bs-toggle="modal" data-bs-target="#editPettyCashModal" data-bs-dismiss="modal"
                                class="btn w-100 py-3 rounded-3 fw-bold d-flex flex-column align-items-center justify-content-center h-100 shift-btn-standard"
                                onmousedown="this.style.transform='scale(0.98)'"
                                onmouseup="this.style.transform='scale(1)'">
                                <div class="shift-text-muted mb-1" style="font-size: 0.75rem;"><i class="bi bi-pencil-square me-1"></i> Petty Cash</div>
                                <div class="shift-text-main" style="font-size: 1.15rem;">
                                    ₱{{ number_format($pettyCash->beginning_balance, 2) }}</div>
                            </button>
                        </div>

                        <!-- End Shift / Logout -->
                        <div class="col-md-12">
                            <button type="button"
                                data-bs-toggle="modal" data-bs-target="#endShiftModal"
                                data-bs-dismiss="modal"
                                class="btn w-100 py-3 rounded-3 fw-bold d-flex flex-column align-items-center justify-content-center h-100 shift-btn-exit"
                                onmousedown="this.style.transform='scale(0.98)'"
                                onmouseup="this.style.transform='scale(1)'">
                                <i class="bi bi-box-arrow-right mb-1" style="color: #ef4444; font-size: 1.4rem;"></i>
                                End Shift / Logout
                            </button>
                        </div>
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>

<!-- End Shift / Logout Summary Modal -->
<div class="modal fade" id="endShiftModal" tabindex="-1" aria-labelledby="endShiftModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shift-modal-content">
            <div class="modal-body p-4 p-md-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-info-circle-fill" style="font-size: 3rem; color: #0284c7;"></i>
                    <h4 class="fw-bold shift-text-main mt-3">Shift Summary</h4>
                    <p class="shift-text-muted">Please review your totals for today before logging out.</p>
                </div>

                <div class="shift-card p-3 mb-3 d-flex justify-content-between align-items-center text-start">
                    <div>
                        <div class="shift-text-muted mb-1" style="font-size: 0.85rem; font-weight: 600;">Petty Cash</div>
                        <div class="fw-bold shift-text-main" style="font-size: 1.25rem;">₱{{ $pettyCash ? number_format($pettyCash->beginning_balance, 2) : '0.00' }}</div>
                    </div>
                    <i class="bi bi-cash-stack" style="font-size: 1.5rem; color: #a855f7;"></i>
                </div>

                <div class="shift-card p-3 mb-4 d-flex justify-content-between align-items-center text-start">
                    <div>
                        <div class="shift-text-muted mb-1" style="font-size: 0.85rem; font-weight: 600;">Total Sales Today</div>
                        <div class="fw-bold shift-text-main" style="font-size: 1.25rem;">₱{{ number_format($salesToday ?? 0, 2) }}</div>
                    </div>
                    <i class="bi bi-graph-up-arrow" style="font-size: 1.5rem; color: #22c55e;"></i>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn w-50 py-3 rounded-3 fw-bold shift-btn-standard" data-bs-toggle="modal" data-bs-target="#shiftModal" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form action="{{ route('cashier.logout') }}" method="POST" class="w-50 m-0">
                        @csrf
                        <button type="submit" class="btn w-100 py-3 rounded-3 fw-bold text-white" style="background-color: #ef4444; border: none; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                            Confirm Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Details Modal -->
<div class="modal fade" id="salesDetailsModal" tabindex="-1" aria-labelledby="salesDetailsModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shift-modal-content">
            <div class="modal-body p-4 p-md-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-pie-chart-fill" style="font-size: 3rem; color: #3b82f6;"></i>
                    <h4 class="fw-bold shift-text-main mt-3">Sales Breakdown</h4>
                    <p class="shift-text-muted">Here is the detailed breakdown of your total sales for today.</p>
                </div>

                <div class="shift-card p-3 mb-3 d-flex justify-content-between align-items-center text-start">
                    <div>
                        <div class="shift-text-muted mb-1" style="font-size: 0.85rem; font-weight: 600;">Walk-in Sales</div>
                        <div class="fw-bold shift-text-main" style="font-size: 1.25rem;">₱{{ number_format($walkInSales ?? 0, 2) }}</div>
                    </div>
                    <i class="bi bi-shop" style="font-size: 1.5rem; color: #f59e0b;"></i>
                </div>

                <div class="shift-card p-3 mb-4 d-flex justify-content-between align-items-center text-start">
                    <div>
                        <div class="shift-text-muted mb-1" style="font-size: 0.85rem; font-weight: 600;">Online Sales</div>
                        <div class="fw-bold shift-text-main" style="font-size: 1.25rem;">₱{{ number_format($onlineSales ?? 0, 2) }}</div>
                    </div>
                    <i class="bi bi-globe" style="font-size: 1.5rem; color: #10b981;"></i>
                </div>

                <div class="shift-card p-3 mb-4 d-flex justify-content-between align-items-center text-start" style="border: 2px solid #3b82f6;">
                    <div>
                        <div class="shift-text-muted mb-1" style="font-size: 0.85rem; font-weight: 600; color: #3b82f6 !important;">Total Combined</div>
                        <div class="fw-bold shift-text-main" style="font-size: 1.25rem;">₱{{ number_format($salesToday ?? 0, 2) }}</div>
                    </div>
                    <i class="bi bi-calculator-fill" style="font-size: 1.5rem; color: #3b82f6;"></i>
                </div>

                <button type="button" class="btn w-100 py-3 rounded-3 fw-bold shift-btn-standard" data-bs-toggle="modal" data-bs-target="#shiftModal" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left me-1"></i> Back to Shift Summary
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Petty Cash Modal -->
<div class="modal fade" id="editPettyCashModal" tabindex="-1" aria-labelledby="editPettyCashModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shift-modal-content">
            <div class="modal-body p-4 p-md-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-pencil-square" style="font-size: 3rem; color: #f59e0b;"></i>
                    <h4 class="fw-bold shift-text-main mt-3">Edit Petty Cash</h4>
                    <p class="shift-text-muted">Update your starting petty cash for today.</p>
                </div>

                <form action="{{ route('cashier.pos.edit_petty_cash') }}" method="POST">
                    @csrf
                    <div class="shift-card p-4 mb-4 text-center">
                        <label class="shift-text-muted fw-bold mb-3 d-block" style="font-size: 0.9rem;">NEW BALANCE / PETTY CASH</label>
                        <div class="input-group input-group-lg mx-auto" style="max-width: 300px;">
                            <span class="input-group-text bg-light border-end-0 fw-bold">₱</span>
                            <input type="number" name="beginning_balance" class="form-control border-start-0 fw-bold text-center" value="{{ $pettyCash ? $pettyCash->beginning_balance : '' }}" required min="0" step="0.01" style="font-size: 1.5rem;" autofocus>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="button" class="btn w-50 py-3 rounded-3 fw-bold shift-btn-standard" data-bs-toggle="modal" data-bs-target="#shiftModal" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn w-50 py-3 rounded-3 fw-bold text-white" style="background-color: #f59e0b; border: none; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time Clock Update
        const clockDisplay = document.getElementById('realtime-clock');
        function updateClock() {
            if (clockDisplay) {
                const now = new Date();
                const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
                clockDisplay.innerText = now.toLocaleString('en-US', options).replace(' at ', ' - ');
            }
        }
        updateClock();
        setInterval(updateClock, 60000); // Update every minute

        // Shift Duration Logic (Only if pettyCash exists)
        @if($pettyCash)
            const shiftStart = new Date("{{ \Carbon\Carbon::parse($pettyCash->opening_time)->format('Y-m-d H:i:s') }}").getTime();
            const display = document.getElementById('shiftDurationDisplay');

            function updateDuration() {
                const now = new Date().getTime();
                const diff = Math.max(0, now - shiftStart);

                const h = Math.floor(diff / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                if (display) {
                    display.innerText =
                        String(h).padStart(2, '0') + ':' +
                        String(m).padStart(2, '0') + ':' +
                        String(s).padStart(2, '0');
                }
            }

            updateDuration();
            setInterval(updateDuration, 1000);
        @endif
    });
</script>
