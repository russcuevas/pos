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
                @php
                    $fullname = Auth::guard('admin')->user()->fullname ?? 'Admin';
                    $initials = collect(explode(' ', $fullname))
                        ->map(function ($segment) {
                            return strtoupper(substr($segment, 0, 1));
                        })
                        ->take(2)
                        ->join('');
                @endphp
                <div class="user-avatar">{{ $initials }}</div>
                <div class="user-menu-trigger-text">
                    <div class="user-menu-trigger-name">{{ $fullname }}</div>
                    <div class="user-menu-trigger-role">Administrator</div>
                </div>
                <i class="bi bi-chevron-down user-menu-trigger-chevron"></i>
            </button>
            <div class="user-menu-dropdown" id="userMenuDropdown">
                <button class="user-menu-item" type="button">
                    <i class="bi bi-person"></i>
                    Profile
                </button>
                <form action="{{ route('admin.logout') }}" method="POST" class="m-0 p-0 w-100">
                    @csrf
                    <button class="user-menu-item logout w-100 text-start border-0 bg-transparent" type="submit"
                        style="font-family: inherit; font-size: inherit;">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
