<nav class="top-nav">
    <a href="{{ route('customers.home.page') }}" class="nav-brand">
        <i class="bi bi-shop" style="font-size: 1.4rem;"></i>
        <div class="d-flex flex-column" style="line-height: 1.2;">
            <span class="nav-text">Products</span>
            <small style="font-size: 0.65rem; font-weight: 500; opacity: 0.85;">Welcome,
                {{ Auth::guard('web')->user()->fullname }}</small>
        </div>
    </a>

    <!-- Right Side Actions (Always Visible) -->
    <div class="d-flex align-items-center gap-2 ms-auto">
        <!-- Home -->
        <a href="{{ route('customers.home.page') }}"
            class="nav-icon-pill {{ request()->routeIs('customers.home.page') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>
        </a>

        <!-- Receipt -->
        <a href="#" class="nav-icon-pill">
            <i class="bi bi-receipt"></i>
        </a>

        <!-- Cart -->
        <a href="#" class="nav-icon-pill" id="cartToggle">
            <i class="bi bi-cart"></i>
        </a>

        <!-- Theme Toggle -->
        <button class="theme-toggle" id="posThemeToggle" type="button" aria-label="Toggle dark mode"
            style="background:transparent;border:none;color:white;font-size:1.2rem;cursor:pointer;">
            <i class="bi bi-moon-stars" id="posThemeToggleIcon"></i>
        </button>

        <!-- Logout -->
        <form action="{{ route('customers.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="nav-icon-pill border-0 bg-transparent" style="color: white; padding: 0;">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</nav>
