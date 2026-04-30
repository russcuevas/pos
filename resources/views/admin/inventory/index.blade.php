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
    @include('admin.components.left_sidebar')

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• TOPBAR â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    @include('admin.components.navbar')

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• MAIN CONTENT â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <main class="main-content">

        <div class="row g-3 mb-4">
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-boxes"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $totalProducts }}</div>
                        <div class="stat-label">Total Products</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-shield-check"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $safeCount }}</div>
                        <div class="stat-label">Safe</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $lowCount }}</div>
                        <div class="stat-label">Low</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-icon cobalt"><i class="bi bi-exclamation-octagon-fill"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $criticalCount }}</div>
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
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="product-thumb"
                                            style="width: 52px; height: 52px; border-radius: 12px; overflow: hidden; background: var(--border); flex-shrink: 0; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                            @if ($product->product_image)
                                                <img src="{{ asset('images/products/' . $product->product_image) }}"
                                                    alt="{{ $product->product_name }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                📦
                                            @endif
                                        </div>
                                        <div>
                                            <div class="product-title"
                                                style="font-weight: 700; color: var(--mine-shaft); font-size: 0.95rem; margin-bottom: 4px; line-height: 1.2;">
                                                {{ $product->product_name }}</div>
                                            <div class="d-flex align-items-center gap-2"
                                                style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                                                @if ($product->quantity <= 10)
                                                    <span
                                                        style="background: rgba(239, 68, 68, 0.15); color: #dc2626; padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.2);">Stock:
                                                        {{ number_format($product->quantity, 0) }}</span>
                                                @elseif($product->quantity <= 50)
                                                    <span
                                                        style="background: rgba(245, 158, 11, 0.15); color: #d97706; padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(245, 158, 11, 0.2);">Stock:
                                                        {{ number_format($product->quantity, 0) }}</span>
                                                @else
                                                    <span
                                                        style="background: rgba(16, 185, 129, 0.15); color: #059669; padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(16, 185, 129, 0.2);">Stock:
                                                        {{ number_format($product->quantity, 0) }}</span>
                                                @endif
                                                <span>•</span>
                                                <span>Sold: {{ number_format($product->sold_this_month, 0) }} this month</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editProductModal{{ $product->id }}">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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
