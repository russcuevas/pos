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
            <a class="nav-link {{ request()->routeIs('admin.dashboard.page') ? 'active' : '' }}"
                href="{{ route('admin.dashboard.page') }}">
                <i class="bi bi-grid-1x2-fill nav-icon"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link {{ request()->is('admin/cashiers*') ? 'active' : '' }}"
                href="{{ url('/admin/cashiers') }}">
                <i class="bi bi-people nav-icon"></i>
                <span>Cashiers</span>
            </a>
            <a class="nav-link {{ request()->is('admin/pos*') ? 'active' : '' }}" href="{{ url('/admin/pos') }}">
                <i class="bi bi-cart-check nav-icon"></i>
                <span>POS</span>
            </a>
            <a class="nav-link {{ request()->is('admin/analytics*') ? 'active' : '' }}"
                href="{{ url('/admin/analytics') }}">
                <i class="bi bi-bar-chart-line nav-icon"></i>
                <span>Analytics & Reports</span>
            </a>
            <a class="nav-link {{ request()->is('admin/debtors*') ? 'active' : '' }}"
                href="{{ url('/admin/debtors') }}">
                <i class="bi bi-people nav-icon"></i>
                <span>Debtors</span>
            </a>
            <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}"
                href="{{ url('/admin/orders') }}">
                <i class="bi bi-receipt nav-icon"></i>
                <span>View Orders</span>
            </a>
            <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}"
                href="{{ url('/admin/products') }}">
                <i class="bi bi-box-seam nav-icon"></i>
                <span>Product List</span>
            </a>
            <a class="nav-link {{ request()->is('admin/inventory*') ? 'active' : '' }}"
                href="{{ url('/admin/inventory') }}">
                <i class="bi bi-boxes nav-icon"></i>
                <span>Inventory</span>
            </a>
            @php
                $pendingOrdersCount = \App\Models\Orders::whereIn('order_status', ['Pending', 'Preparing', 'Ready'])
                    ->distinct('order_number')
                    ->count('order_number');
            @endphp
            <a class="nav-link {{ request()->is('admin/pending_orders*') ? 'active' : '' }}"
                href="{{ url('/admin/pending_orders') }}">
                <i class="bi bi-hourglass-split nav-icon"></i>
                <span>Pending Orders</span>
                @if ($pendingOrdersCount > 0)
                    <span class="badge bg-info rounded-pill ms-auto pending-badge">{{ $pendingOrdersCount }}</span>
                @endif
            </a>
            <a class="nav-link {{ request()->is('admin/finance*') ? 'active' : '' }}"
                href="{{ url('/admin/finance') }}">
                <i class="bi bi-cash-stack nav-icon"></i>
                <span>Finance</span>
            </a>
            <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}"
                href="{{ url('/admin/settings') }}">
                <i class="bi bi-person-gear nav-icon"></i>
                <span>Setting</span>
            </a>
        </nav>

    </div>
</aside>
