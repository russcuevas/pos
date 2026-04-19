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
</head>

<body>

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• SIDEBAR â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="bi bi-shop-window"></i></div>
            <div class="brand-text">
                <p>POS SYSTEM</p>
                <h6>Sam's Store</h6>
            </div>
        </div>

        <div style="overflow-y:auto; flex:1; padding-bottom:10px;">
            <div class="nav-section-label">Main Menu</div>
            <nav class="nav flex-column px-0" style="gap:2px;">
                <a class="nav-link" href="{{ route('admin.dashboard.page') }}">
                    <i class="bi bi-grid-1x2-fill nav-icon"></i>
                    <span>Dashboard</span>
                </a>
                <a class="nav-link active" href="{{ url('/admin/cashiers') }}">
                    <i class="bi bi-people nav-icon"></i>
                    <span>Cashiers</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/pos') }}">
                    <i class="bi bi-cart-check nav-icon"></i>
                    <span>POS</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/analytics') }}">
                    <i class="bi bi-bar-chart-line nav-icon"></i>
                    <span>Analytics</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/debtors') }}">
                    <i class="bi bi-people nav-icon"></i>
                    <span>Debtors</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/orders') }}">
                    <i class="bi bi-receipt nav-icon"></i>
                    <span>View Orders</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/products') }}">
                    <i class="bi bi-box-seam nav-icon"></i>
                    <span>Product List</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/inventory') }}">
                    <i class="bi bi-boxes nav-icon"></i>
                    <span>Inventory</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/pending-orders') }}">
                    <i class="bi bi-hourglass-split nav-icon"></i>
                    <span>Pending Orders</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/finance') }}">
                    <i class="bi bi-cash-stack nav-icon"></i>
                    <span>Finance</span>
                </a>
                <a class="nav-link" href="{{ url('/admin/settings') }}">
                    <i class="bi bi-person-gear nav-icon"></i>
                    <span>Setting</span>
                </a>
            </nav>

        </div>
    </aside>

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• TOPBAR â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <header class="topbar">
        <button class="toggle-btn" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-actions">
            <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode">
                <i class="bi bi-moon-stars" id="themeToggleIcon"></i>
            </button>
            <div class="user-menu" id="userMenu">
                <button class="user-menu-trigger" id="userMenuToggle" type="button" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="user-avatar">ES</div>
                    <div class="user-menu-trigger-text">
                        <div class="user-menu-trigger-name">Erbil Sam</div>
                        <div class="user-menu-trigger-role">Administrator</div>
                    </div>
                    <i class="bi bi-chevron-down user-menu-trigger-chevron"></i>
                </button>
                <div class="user-menu-dropdown" id="userMenuDropdown">
                    <button class="user-menu-item" type="button">
                        <i class="bi bi-person"></i>
                        Profile
                    </button>
                    <button class="user-menu-item logout" type="button">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• MAIN CONTENT â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <main class="main-content">

        <!-- Page Header -->
        <div class="page-header">
            <h2>Cashiers</h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- â”€â”€ Recent Training Progress Table â”€â”€ -->
        <div class="panel">
            <div class="panel-header">
                <div>
                    <p class="panel-title">Cashiers List</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCashierModal">
                    <i class="bi bi-plus"></i> Add Cashier
                </button>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table" id="cashiersTable">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashiers as $cashier)
                            <tr>
                                <td>{{ $cashier->fullname }}</td>
                                <td>{{ $cashier->email }}</td>
                                <td>
                                    @if ($cashier->status == 'active')
                                        <span style="color: green">Active</span>
                                    @else
                                        <span style="color: red">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editCashierModal{{ $cashier->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteCashierModal{{ $cashier->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODALS --}}
        @include('admin.cashiers.modals.add')

        @foreach ($cashiers as $cashier)
            @include('admin.cashiers.modals.edit')
            @include('admin.cashiers.modals.delete')
        @endforeach
        {{-- END MODALS --}}
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script src="{{ asset('assets/script.js') }}"></script>
    <script>
        const cashiersTableElement = document.getElementById('cashiersTable');
        let cashiersDataTable;

        function initcashiersDataTable() {
            if (!cashiersTableElement || !window.jQuery || !window.jQuery.fn.DataTable) {
                return;
            }

            if (cashiersDataTable) {
                cashiersDataTable.destroy();
            }

            cashiersDataTable = window.jQuery(cashiersTableElement).DataTable({
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                autoWidth: false,
            });
        }


        initcashiersDataTable();

        // Notyf Toasts
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top',
            },
            duration: 3000
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                notyf.success("{!! addslashes(session('success')) !!}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    notyf.error("{!! addslashes($error) !!}");
                @endforeach
            @endif
        });
    </script>
</body>

</html>
