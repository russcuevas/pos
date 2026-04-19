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
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/pos.css') }}">
</head>

<body class="pos-page">

    <!-- ════════════════════════════
     TOP NAV
════════════════════════════ -->
    <nav class="top-nav">
        <a href="{{ route('admin.dashboard.page') }}" class="nav-brand">
            <span class="back-icon"><i class="bi bi-arrow-left"></i></span>
            <i class="bi bi-grid-3x3-gap-fill"></i> POS
        </a>

        <!-- Hamburger button -->
        <button class="menu-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse"
            data-bs-target="#topNavMenu" aria-expanded="false" aria-controls="topNavMenu">
            <i class="bi bi-list"></i>
        </button>

        <!-- Collapsible Content -->
        <div class="collapse d-lg-flex top-nav-menu flex-grow-1 ms-lg-3" id="topNavMenu">
            <div class="nav-menu-inner d-flex w-100 gap-2">
                <a href="{{ route('admin.dashboard.page') }}" class="nav-pill"><i class="bi bi-speedometer2"></i>
                    Dashboard</a>
                <a href="#" class="nav-pill active"><i class="bi bi-calculator"></i> POS</a>
                <a href="#" class="nav-pill"><i class="bi bi-list-ul"></i> Orders</a>
                <a href="#" class="nav-pill">
                    <i class="bi bi-hourglass-split"></i> Pending
                    <span class="nav-badge">1</span>
                </a>

                <div class="ms-lg-auto d-flex flex-wrap align-items-center gap-2 right-actions">
                    <button class="theme-toggle" id="posThemeToggle" type="button" aria-label="Toggle dark mode"
                        style="background:transparent;border:none;color:white;font-size:1.2rem;cursor:pointer;">
                        <i class="bi bi-moon-stars" id="posThemeToggleIcon"></i>
                    </button>
                    <a href="#" class="nav-icon-pill">
                        <i class="bi bi-printer"></i>
                    </a>
                    <div class="admin-chip"><i class="bi bi-shield-check"></i> ADMIN</div>
                    <a href="#" class="nav-icon-pill"><i class="bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ════════════════════════════
     MAIN LAYOUT
════════════════════════════ -->
    <div class="pos-wrap">

        <!-- ── LEFT: PRODUCTS ── -->
        <div class="products-pane">

            <!-- Search -->
            <div class="search-wrap">
                <i class="bi bi-search si"></i>
                <input type="text" placeholder="Search Product...">
            </div>

            <!-- Alpha Filter (CSS-only radio) -->
            <div class="alpha-row">
                <input type="radio" name="alpha" id="a-all" checked>
                <label class="alpha-lbl" for="a-all">All</label>

                <input type="radio" name="alpha" id="a-A">
                <label class="alpha-lbl" for="a-A">A</label>

                <input type="radio" name="alpha" id="a-B">
                <label class="alpha-lbl" for="a-B">B</label>

                <input type="radio" name="alpha" id="a-C">
                <label class="alpha-lbl" for="a-C">C</label>

                <input type="radio" name="alpha" id="a-D">
                <label class="alpha-lbl" for="a-D">D</label>

                <input type="radio" name="alpha" id="a-F">
                <label class="alpha-lbl" for="a-F">F</label>

                <input type="radio" name="alpha" id="a-M">
                <label class="alpha-lbl" for="a-M">M</label>

                <input type="radio" name="alpha" id="a-R">
                <label class="alpha-lbl" for="a-R">R</label>

                <input type="radio" name="alpha" id="a-W">
                <label class="alpha-lbl" for="a-W">W</label>

                <input type="radio" name="alpha" id="a-Y">
                <label class="alpha-lbl" for="a-Y">Y</label>

                <input type="radio" name="alpha" id="a-Z">
                <label class="alpha-lbl" for="a-Z">Z</label>
            </div>

            <!-- Product Grid -->
            <div class="grid-scroll">
                <div class="products-grid">

                    <!-- Card 1 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🐟</div>
                            <span class="stock-zero">0</span>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Mighty (Red)</div>
                            <div class="pcard-price">₱10.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb"><img
                                    src="https://tse4.mm.bing.net/th/id/OIP.rWR-uAUTI2Es1uZKtvMgmQHaE8?pid=Api&P=0&h=180"
                                    alt=""></div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Century Tuna Flakes in Oil</div>
                            <div class="pcard-price">₱44.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🌶️</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Century Tuna Hot and Spicy</div>
                            <div class="pcard-price">₱44.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🍬</div>
                            <span class="stock-zero">0</span>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Brown Sugar</div>
                            <div class="pcard-price">₱23.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🐟</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Family Sardines Green</div>
                            <div class="pcard-price">₱26.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🥛</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Doreen Condensed Creamer</div>
                            <div class="pcard-price">₱55.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 7 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🫐</div>
                            <span class="stock-zero">0</span>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Dutch Mill Mix Berries</div>
                            <div class="pcard-price">₱25.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 8 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🍚</div>
                            <span class="stock-zero">0</span>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Refined Sugar</div>
                            <div class="pcard-price">₱25.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 9 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🍓</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Dutch Mill Strawberry</div>
                            <div class="pcard-price">₱16.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 10 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🍫</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Choco Knots</div>
                            <div class="pcard-price">₱10.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 11 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🥩</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Argentina Corned Beef</div>
                            <div class="pcard-price">₱38.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 12 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🐟</div>
                            <span class="stock-zero">0</span>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Mighty (White)</div>
                            <div class="pcard-price">₱10.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 13 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🍿</div>
                            <span class="stock-zero">0</span>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Hi-Chee Crunchy Snack</div>
                            <div class="pcard-price">₱12.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 14 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🚬</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Winston Cigarettes Red</div>
                            <div class="pcard-price">₱95.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 15 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🥤</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Yakult Probiotic Drink</div>
                            <div class="pcard-price">₱18.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 16 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🧴</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Zonrox Bleach Original</div>
                            <div class="pcard-price">₱22.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 17 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">🥛</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Alaska Evap Milk</div>
                            <div class="pcard-price">₱28.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                    <!-- Card 18 -->
                    <div class="pcard">
                        <div class="pcard-img-wrap">
                            <div class="pcard-thumb">☕</div>
                        </div>
                        <div class="pcard-body">
                            <div class="pcard-name">Milo Sachet 22g</div>
                            <div class="pcard-price">₱15.00</div>
                            <a href="#" class="btn-add"><i class="bi bi-plus-lg"></i> Add</a>
                        </div>
                    </div>

                </div><!-- /products-grid -->
            </div><!-- /grid-scroll -->
        </div><!-- /products-pane -->


        <!-- ── RIGHT: ORDER PANEL ── -->
        <div class="order-pane offcanvas-lg offcanvas-end" tabindex="-1" id="orderPane"
            aria-labelledby="orderPaneLabel">

            <!-- Header -->
            <div class="order-head d-flex justify-content-between align-items-center">
                <h6 class="order-title mb-0" id="orderPaneLabel"><i class="bi bi-receipt me-1"></i> Current Order
                </h6>
                <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas"
                    data-bs-target="#orderPane" aria-label="Close"></button>
            </div>

            <!-- Order Items List -->
            <div class="order-list">
                <!-- Empty state (shown when no items) -->
                <div class="empty-state">
                    <i class="bi bi-cart3"></i>
                    <p>No items added yet</p>
                    <span class="empty-hint">Click + Add on any product</span>
                </div>

                <!-- Sample pre-filled items to show layout -->
                <!--
            <div class="order-item">
                <div class="oi-emoji">🥫</div>
                <div class="oi-info">
                    <div class="oi-name">Century Tuna Flakes in Oil</div>
                    <div class="oi-sub">₱44.00 × 2</div>
                </div>
                <div class="oi-controls">
                    <a href="#" class="qty-ctrl"><i class="bi bi-dash"></i></a>
                    <span class="qty-val">2</span>
                    <a href="#" class="qty-ctrl"><i class="bi bi-plus"></i></a>
                </div>
                <div class="oi-price">₱88.00</div>
                <button class="oi-del"><i class="bi bi-x-lg"></i></button>
            </div>
            -->
            </div>

            <!-- Total -->
            <div class="order-total-bar">
                <div class="total-row">
                    <span class="total-lbl">Total</span>
                    <span class="total-val">₱0.00</span>
                </div>
                <hr class="divider-dots">
            </div>

            <!-- Actions -->
            <div class="order-actions">
                <button class="btn-checkout" disabled>
                    <i class="bi bi-bag-check"></i> Checkout
                </button>
                <div class="bottom-actions">
                    <button class="bot-btn cam"><i class="bi bi-camera"></i> Camera</button>
                    <button class="bot-btn cust"><i class="bi bi-plus-circle"></i> Custom</button>
                    <button class="bot-btn sav"><i class="bi bi-folder2-open"></i> Saved</button>
                </div>
            </div>

        </div><!-- /order-pane -->

    </div><!-- /pos-wrap -->

    <!-- Mobile View Cart FAB Button -->
    <button class="cart-fab d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#orderPane"
        aria-controls="orderPane">
        <i class="bi bi-cart3"></i>
        <span class="cart-fab-badge">0</span>
    </button>

    <script>
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
