<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Barlow+Condensed:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/customers-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/customers-home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        .qty-quick-btn {
            background-color: #e0f2fe;
            color: #0284c7;
            border: none;
            border-radius: 8px;
            width: 50px;
            height: 45px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .qty-quick-btn:hover {
            background-color: #bae6fd;
            color: #0369a1;
        }

        .dark-mode .modal-content {
            background-color: var(--pos-bg);
            border: 1px solid var(--pos-border);
        }

        .dark-mode #qtyModalLabel {
            color: var(--text-primary) !important;
        }

        .dark-mode .qty-quick-btn {
            background-color: rgba(2, 132, 199, 0.2);
            color: #38bdf8;
        }

        .dark-mode .qty-quick-btn:hover {
            background-color: rgba(2, 132, 199, 0.4);
            color: #7dd3fc;
        }

        .dark-mode #customQtyInput {
            background-color: var(--pos-bg-alt);
            color: var(--text-primary);
            border-color: var(--pos-border) !important;
        }
    </style>
</head>

<body class="pos-page">

    <!-- ════════════════════════════
     TOP NAV
════════════════════════════ -->

    @include('customers.components.navbar')

    <!-- ════════════════════════════
     MAIN LAYOUT
════════════════════════════ -->
    <div class="pos-wrap">

        <!-- ── LEFT: PRODUCTS ── -->
        <div class="products-pane">
            <div class="search-wrap">
                <i class="bi bi-search si"></i>
                <input type="text" id="productSearch" placeholder="Search for products...">
            </div>

            <div class="grid-scroll">
                <div class="products-grid">
                    @foreach ($products as $product)
                        <div class="pcard {{ $product->quantity <= 0 ? 'out-of-stock' : '' }}">
                            <div class="pcard-img-wrap">
                                @if ($product->product_image)
                                    <img src="{{ asset('images/products/' . $product->product_image) }}"
                                        class="pcard-img" alt="{{ $product->product_name }}">
                                @else
                                    <div class="pcard-thumb">📦</div>
                                @endif

                                @if ($product->quantity <= 0)
                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; backdrop-filter: grayscale(1);">
                                    </div>
                                @endif
                            </div>
                            <div class="pcard-body">
                                <div class="pcard-name">{{ $product->product_name }}</div>
                                <div class="pcard-price">₱{{ number_format($product->selling_price, 2) }}</div>

                                @if ($product->quantity > 0)
                                    <button class="btn-add">
                                        <i class="bi bi-plus"></i> Add
                                    </button>
                                @else
                                    <button class="btn-add disabled" disabled
                                        style="background: #e2e8f0; color: #64748b; border-color: #cbd5e1; cursor: not-allowed;">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="noProductsFound" class="text-center py-5" style="display: none;">
                    <i class="bi bi-box2 fs-1 text-muted" style="opacity: 0.5;"></i>
                    <h5 class="mt-3 text-muted" style="font-weight: 600;">No products found</h5>
                    <p class="text-muted small">Try adjusting your search or category filter.</p>
                </div>
            </div>
        </div><!-- /products-pane -->

    </div><!-- /pos-wrap -->



    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Notyf Initialization
        const notyf = new Notyf({
            position: {
                x: 'center',
                y: 'top'
            },
            duration: 3000,
            types: [{
                type: 'success',
                background: '#10b981',
                icon: {
                    className: 'bi bi-check-circle fs-5 text-white',
                    tagName: 'i'
                }
            }]
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                notyf.success("{!! addslashes(session('success')) !!}");
            @endif

            @if (session('error'))
                notyf.error("{!! addslashes(session('error')) !!}");
            @endif
        });

        const THEME_KEY = 'naap-theme';
        const themeBtn = document.getElementById('posThemeToggle');
        const themeIcon = document.getElementById('posThemeToggleIcon');

        function applyTheme(theme) {
            document.body.classList.toggle('dark-mode', theme === 'dark');
            document.documentElement.setAttribute('data-bs-theme', theme);
            if (themeIcon) {
                themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        }

        const storedTheme = localStorage.getItem(THEME_KEY) || 'light';
        applyTheme(storedTheme);

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                const isDark = document.body.classList.contains('dark-mode');
                const nextTheme = isDark ? 'light' : 'dark';
                localStorage.setItem(THEME_KEY, nextTheme);
                applyTheme(nextTheme);
            });
        }

        // Product Search Functionality (Vanilla JS)
        document.getElementById('productSearch').addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.products-grid .pcard');
            const emptyState = document.getElementById('noProductsFound');
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.querySelector('.pcard-name').textContent.toLowerCase();
                if (name.includes(query)) {
                    card.style.display = "";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            });

            // Show/hide empty state
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? "block" : "none";
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
