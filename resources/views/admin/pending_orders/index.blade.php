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
                <a href="{{ route('admin.pos.page') }}" class="nav-pill"><i class="bi bi-calculator"></i> POS</a>
                <a href="#" class="nav-pill"><i class="bi bi-list-ul"></i> Orders</a>
                <a href="{{ route('admin.pending_orders.page') }}" class="nav-pill active">
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
                    <div class="admin-chip"><i class="bi bi-shield-check"></i>
                        {{ strtoupper(Auth::guard('admin')->user()->fullname) }}</div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-icon-pill border-0 bg-transparent" style="color: white;">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
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




        </div><!-- /order-pane -->

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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
