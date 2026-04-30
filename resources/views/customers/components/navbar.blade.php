<nav class="top-nav">
    <a href="{{ route('customers.home.page') }}" class="nav-brand">
        <i class="bi bi-shop" style="font-size: 1.4rem;"></i>
        <div class="d-flex flex-column" style="line-height: 1.2;">
            <span class="nav-text">Products</span>
            <small style="font-size: 0.65rem; font-weight: 500; opacity: 0.85;">Welcome,
                {{ Auth::guard('web')->user()->fullname }}</small>
        </div>
    </a>

    <!-- Hamburger button -->
    <button class="menu-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#topNavMenu"
        aria-expanded="false" aria-controls="topNavMenu">
        <i class="bi bi-list"></i>
    </button>

    <!-- Collapsible Content -->
    <div class="collapse d-lg-flex top-nav-menu flex-grow-1 ms-lg-3" id="topNavMenu">
        <div class="nav-menu-inner d-flex w-100 gap-2">

            <div class="ms-lg-auto d-flex flex-wrap align-items-center gap-2 right-actions">

                <a href="{{ route('customers.home.page') }}"
                    class="nav-icon-pill {{ request()->routeIs('customers.home.page') ? 'active' : '' }}">
                    <i class="bi bi-house-fill"></i>
                </a>
                <a href="#" class="nav-icon-pill">
                    <i class="bi bi-receipt"></i>
                </a>
                <a href="#" class="nav-icon-pill">
                    <i class="bi bi-cart"></i>
                </a>
                <button class="theme-toggle" id="posThemeToggle" type="button" aria-label="Toggle dark mode"
                    style="background:transparent;border:none;color:white;font-size:1.2rem;cursor:pointer;">
                    <i class="bi bi-moon-stars" id="posThemeToggleIcon"></i>
                </button>
                <form action="{{ route('customers.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-icon-pill border-0 bg-transparent" style="color: white;">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
