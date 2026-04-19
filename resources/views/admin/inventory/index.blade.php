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
                <a class="nav-link active" href="{{ url('/admin/inventory') }}">
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

        <div class="row g-3 mb-4">
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-boxes"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">1</div>
                        <div class="stat-label">Total Products</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-shield-check"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">2</div>
                        <div class="stat-label">Safe</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">3</div>
                        <div class="stat-label">Low</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon cobalt"><i class="bi bi-exclamation-octagon-fill"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">2</div>
                        <div class="stat-label">Critical</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h2>Inventory</h2>
        </div>

        <!-- â”€â”€ Recent Training Progress Table â”€â”€ -->
        <div class="panel">
            <div class="panel-header">
                <div>
                    <p class="panel-title">Inventory List</p>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table" id="productTable">
                    <thead>
                        <tr>
                            <th>Products</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-thumb"
                                        style="width: 52px; height: 52px; border-radius: 12px; overflow: hidden; background: var(--border); flex-shrink: 0; box-shadow: var(--shadow-sm);">
                                        <img src="https://tse4.mm.bing.net/th/id/OIP.rWR-uAUTI2Es1uZKtvMgmQHaE8?pid=Api&P=0&h=180"
                                            alt="Product" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div>
                                        <div class="product-title"
                                            style="font-weight: 700; color: var(--mine-shaft); font-size: 0.95rem; margin-bottom: 4px; line-height: 1.2;">
                                            Century Tuna Flakes in Oil</div>
                                        <div class="d-flex align-items-center gap-2"
                                            style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                                            <span
                                                style="background: rgba(16, 185, 129, 0.15); color: #059669; padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(16, 185, 129, 0.2);">Stock:
                                                125</span>
                                            <span>•</span>
                                            <span>Sold: 42 this month</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editProductModal">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    <script>
        const productTableElement = document.getElementById('productTable');
        let productDataTable;

        function initproductDataTable() {
            if (!productTableElement || !window.jQuery || !window.jQuery.fn.DataTable) {
                return;
            }

            if (productDataTable) {
                productDataTable.destroy();
            }

            productDataTable = window.jQuery(productTableElement).DataTable({
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                autoWidth: false,
            });
        }


        initproductDataTable();
    </script>
</body>

</html>
