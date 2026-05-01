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
    @include('admin.components.left_sidebar')

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• TOPBAR â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    @include('admin.components.navbar')

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• MAIN CONTENT â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <main class="main-content">

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-boxes"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $totalProducts }}</div>
                        <div class="stat-label">Total Products</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-shield-check"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $safeCount }}</div>
                        <div class="stat-label">Safe</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="stat-body">
                        <div class="stat-value">{{ $lowCount }}</div>
                        <div class="stat-label">Low</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
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
                                        data-bs-target="#stockManagementModal{{ $product->id }}"
                                        style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: #0ea5e9; border: none;">
                                        <i class="bi bi-plus" style="font-size: 1.2rem;"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Stock Management Modals -->
    @foreach ($products as $product)
        <div class="modal fade stock-modal" id="stockManagementModal{{ $product->id }}" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Stock for {{ $product->product_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="stock-card">
                            <p style="font-weight: 600; margin-bottom: 12px; font-size: 0.95rem;">Add
                                New Stock</p>
                            <form action="{{ route('admin.inventory.addStock', $product->id) }}" method="POST">
                                @csrf
                                <div class="stock-input-group">
                                    <div class="stock-input-field">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" required placeholder="0">
                                    </div>
                                    <div class="stock-input-field">
                                        <label>Cost per Item (₱)</label>
                                        <input type="number" step="0.01" name="cost_price" required
                                            value="{{ $product->supplier_price }}">
                                    </div>
                                    <button type="submit" class="btn-add-stock">Add Stock</button>
                                </div>
                            </form>
                        </div>

                        <h6 class="stock-batches-title">Stock Batches</h6>

                        <div class="stock-batches-list" style="max-height: 400px; overflow-y: auto; padding-right: 4px;">
                            @forelse($product->stockHistory as $history)
                                <div class="stock-batch-card">
                                    <div class="stock-batch-header">
                                        <span class="stock-batch-date">Received:
                                            {{ $history->created_at->format('m/d/Y') }}</span>
                                        <form action="{{ route('admin.inventory.deleteStock', $history->id) }}"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-stock">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <form action="{{ route('admin.inventory.updateStock', $history->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="stock-input-group">
                                            <div class="stock-input-field">
                                                <label>Quantity</label>
                                                <input type="number" name="quantity" value="{{ $history->quantity }}"
                                                    required>
                                            </div>
                                            <div class="stock-input-field">
                                                <label>Cost per Item</label>
                                                <input type="number" step="0.01" name="cost_price"
                                                    value="{{ $history->cost_price }}" required>
                                            </div>
                                            <button type="submit" class="btn-save-stock">Save</button>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-box-seam d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                    No stock batches found.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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
