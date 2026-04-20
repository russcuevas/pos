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

        <!-- Page Header -->
        <div class="page-header">
            <h2>Products</h2>
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
                    <p class="panel-title">Product List</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus"></i> Add Product
                </button>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table" id="productTable">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Show</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->product_code }}</td>
                                <td>
                                    @if ($product->product_image)
                                        <img src="{{ asset('images/products/' . $product->product_image) }}"
                                            alt="Product Image" style="max-height: 50px; border-radius: 5px;">
                                    @else
                                        <span>No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>
                                    @if ($product->is_show)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#viewProductModal{{ $product->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editProductModal{{ $product->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteProductModal{{ $product->id }}">
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
        @include('admin.products.modals.add')
        @foreach ($products as $product)
            @include('admin.products.modals.edit')
            @include('admin.products.modals.view')
            @include('admin.products.modals.delete')
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
                    [4, 'desc']
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

        function previewImage(input, previewId, iconId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).style.backgroundImage = 'url(' + e.target.result + ')';
                    if (document.getElementById(iconId)) {
                        document.getElementById(iconId).style.display = 'none';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
