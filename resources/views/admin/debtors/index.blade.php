<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        .fab-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #d93025 0%, #b71c1c 100%);
            color: white !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 10px 25px rgba(217, 48, 37, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .fab-btn:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 15px 30px rgba(217, 48, 37, 0.6);
        }

        .fab-btn:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body>

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• SIDEBAR â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    @include('admin.components.left_sidebar')

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• TOPBAR â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    @include('admin.components.navbar')

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• MAIN CONTENT â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <main class="main-content">

        <!-- Page Header -->
        <div class="page-header">
            <h2>Debtors</h2>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-6">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $debtors->where('balance', '>', 0)->count() }}</div>
                        <div class="stat-label">With Balance</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-6">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">&#8369;{{ number_format($debtors->sum('balance'), 2) }}</div>
                        <div class="stat-label">Total Outstanding Balance</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <p class="panel-title">Debtor Accounts</p>
                    <p class="panel-subtitle">Manage customer credit and balances</p>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table" id="debtorsTable">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($debtors as $debtor)
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <div class="student-avatar"
                                            style="background: linear-gradient(135deg, #d93025 0%, #b71c1c 100%)">
                                            {{ strtoupper(substr($debtor->customer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="student-name">{{ $debtor->customer_name }}</div>
                                            <div class="student-id">ID:
                                                #{{ str_pad($debtor->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $debtor->customer_contact }}</td>
                                <td title="{{ $debtor->customer_address }}">
                                    {{ Str::limit($debtor->customer_address, 30) }}</td>
                                <td class="fw-bold text-danger">&#8369;{{ number_format($debtor->balance, 2) }}</td>
                                <td class="d-flex align-items-center gap-2">
                                    <a href="{{ route('admin.debtors.view', $debtor->id) }}"
                                        class="btn btn-sm btn-light rounded-pill px-3 fw-bold">View Details</a>

                                    @if ($debtor->balance == 0 && $debtor->transactions_count == 0 && $debtor->items_count == 0)
                                        <form action="{{ route('admin.debtors.delete', $debtor->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger border-0 rounded-circle p-1"
                                                title="Delete Account">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <a href="#" class="fab-btn" title="Add Debtor" data-bs-toggle="modal" data-bs-target="#addDebtorModal">
            <i class="bi bi-plus-lg"></i>
        </a>

        <!-- Add Debtor Modal -->
        <div class="modal fade" id="addDebtorModal" tabindex="-1" aria-labelledby="addDebtorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="modal-header border-0"
                        style="background: linear-gradient(135deg, #d93025 0%, #b71c1c 100%); color: white;">
                        <h5 class="modal-title fw-bold" id="addDebtorModalLabel">Create Debtor Account</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.debtors.create') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Customer Name</label>
                                <input type="text" name="customer_name"
                                    class="form-control rounded-3 border-light shadow-sm" placeholder="Enter full name"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contact Number</label>
                                <input type="text" name="customer_contact"
                                    class="form-control rounded-3 border-light shadow-sm" placeholder="e.g. 09123456789"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="customer_address" class="form-control rounded-3 border-light shadow-sm" rows="3"
                                    placeholder="Enter complete address" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light rounded-3 fw-bold"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white rounded-3 fw-bold px-4"
                                style="background: #d93025;">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top'
            },
            duration: 3000
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                notyf.success("{!! addslashes(session('success')) !!}");
            @endif

            @if (session('error'))
                notyf.error("{!! addslashes(session('error')) !!}");
            @endif
        });
    </script>

    <script src="{{ asset('assets/script.js') }}"></script>
    <script>
        const debtorsTableElement = document.getElementById('debtorsTable');
        let debtorsDataTable;

        function initDebtorsDataTable() {
            if (!debtorsTableElement || !window.jQuery || !window.jQuery.fn.DataTable) {
                return;
            }

            if (debtorsDataTable) {
                debtorsDataTable.destroy();
            }

            debtorsDataTable = window.jQuery(debtorsTableElement).DataTable({
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                autoWidth: false,
            });
        }


        initDebtorsDataTable();
    </script>
</body>

</html>
