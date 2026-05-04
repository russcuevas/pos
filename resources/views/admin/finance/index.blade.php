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

        <style>
            /* Light Mode (Default) */
            .finance-container {
                padding: 30px;
                background-color: #ffffff;
                border-radius: 16px;
                color: #1e293b;
                min-height: 80vh;
                transition: background-color 0.3s, color 0.3s;
            }

            .finance-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .finance-title {
                font-size: 1.5rem;
                font-weight: 800;
                color: #0f172a;
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 0;
            }

            .badge-records {
                background-color: #3b82f6;
                font-size: 0.65rem;
                font-weight: 700;
                padding: 4px 8px;
                border-radius: 6px;
                color: white;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .btn-month {
                background-color: transparent;
                color: #475569;
                border: 1px solid #cbd5e1;
                font-size: 0.75rem;
                font-weight: 700;
                padding: 6px 16px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s;
            }

            .btn-month:hover {
                background-color: #f1f5f9;
                color: #0f172a;
                border-color: #94a3b8;
            }

            .filter-pills {
                display: flex;
                gap: 10px;
                margin-bottom: 60px;
            }

            .filter-pill {
                background-color: #f1f5f9;
                color: #64748b;
                border: none;
                padding: 6px 20px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.2s;
            }

            .filter-pill.active {
                background-color: #0f172a;
                color: white;
            }

            .filter-pill:hover:not(.active) {
                background-color: #e2e8f0;
                color: #1e293b;
            }

            .balance-section {
                text-align: center;
                margin-bottom: 60px;
            }

            .balance-label {
                font-size: 0.7rem;
                font-weight: 700;
                color: #64748b;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .balance-amount {
                font-size: 2.5rem;
                font-weight: 900;
                color: #0f172a;
                margin: 0;
            }

            .flow-cards {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            @media (max-width: 768px) {
                .flow-cards {
                    grid-template-columns: 1fr;
                }
            }

            .flow-card {
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 24px;
                transition: all 0.2s;
            }

            .flow-card:hover {
                border-color: #cbd5e1;
            }

            .flow-label {
                font-size: 0.75rem;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .flow-value {
                font-size: 1.25rem;
                font-weight: 800;
            }

            .text-inflow {
                color: #10b981;
            }

            .text-outflow {
                color: #ef4444;
            }

            /* Footer Action Buttons */
            .finance-footer {
                display: flex;
                gap: 20px;
                margin-top: 60px;
            }

            .btn-action {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 15px;
                padding: 20px;
                border-radius: 16px;
                border: none;
                font-size: 1.1rem;
                font-weight: 800;
                color: white;
                cursor: pointer;
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .btn-action:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }

            .btn-income {
                background-color: #10b981;
            }

            .btn-expense {
                background-color: #ef4444;
            }

            .icon-box {
                background-color: rgba(255, 255, 255, 0.25);
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
            }

            @media (max-width: 768px) {
                .finance-footer {
                    flex-direction: column;
                }
            }

            /* Category Breakdown */
            .category-breakdown {
                margin-top: 40px;
            }

            .category-label {
                font-size: 0.75rem;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                margin-bottom: 15px;
                letter-spacing: 0.5px;
            }

            .category-cards {
                display: flex;
                gap: 15px;
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .category-cards::-webkit-scrollbar {
                height: 6px;
            }

            .category-cards::-webkit-scrollbar-track {
                background: transparent;
            }

            .category-cards::-webkit-scrollbar-thumb {
                background-color: #e2e8f0;
                border-radius: 4px;
            }

            .category-card {
                background-color: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 15px;
                min-width: 120px;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .category-header {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .category-icon {
                font-size: 1rem;
            }

            .icon-food {
                color: #f59e0b;
            }

            .icon-sales {
                color: #10b981;
            }

            .category-name {
                font-size: 0.7rem;
                font-weight: 800;
                color: #64748b;
            }

            .category-amount {
                font-size: 1rem;
                font-weight: 800;
                color: #0f172a;
            }

            /* Dark Mode Overrides */
            body.dark-mode .finance-container {
                background-color: #0f172a;
                color: white;
                border-color: #1e293b;
            }

            body.dark-mode .finance-title {
                color: #f8fafc;
            }

            body.dark-mode .btn-month {
                color: #cbd5e1;
                border-color: #334155;
            }

            body.dark-mode .btn-month:hover {
                background-color: #1e293b;
                color: white;
                border-color: #475569;
            }

            body.dark-mode .filter-pill {
                background-color: #1e293b;
                color: #94a3b8;
            }

            body.dark-mode .filter-pill.active {
                background-color: #f8fafc;
                color: #0f172a;
            }

            body.dark-mode .filter-pill:hover:not(.active) {
                background-color: #334155;
                color: white;
            }

            body.dark-mode .balance-label {
                color: #94a3b8;
            }

            body.dark-mode .balance-amount {
                color: #f8fafc;
            }

            body.dark-mode .flow-card {
                background-color: #1e293b;
                border-color: #334155;
            }

            body.dark-mode .flow-card:hover {
                border-color: #475569;
            }

            body.dark-mode .flow-label {
                color: #94a3b8;
            }

            body.dark-mode .text-inflow {
                color: #34d399;
            }

            body.dark-mode .text-outflow {
                color: #f87171;
            }

            body.dark-mode .category-card {
                background-color: #1e293b;
                border-color: #334155;
            }

            body.dark-mode .category-amount {
                color: #f8fafc;
            }

            body.dark-mode .category-cards::-webkit-scrollbar-thumb {
                background-color: #475569;
            }

            /* Modal Styling */
            .custom-modal .modal-content {
                border-radius: 20px;
                border: none;
                background-color: #ffffff;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }

            .custom-modal .modal-title {
                color: #0f172a;
            }

            .custom-radio .form-check-input {
                display: none;
            }

            .custom-radio .form-check-label {
                padding: 12px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                cursor: pointer;
                font-weight: 600;
                color: #64748b;
                transition: all 0.2s;
            }

            .custom-radio .form-check-input:checked+.form-check-label {
                background-color: #10b981;
                color: white;
                border-color: #10b981;
            }

            .custom-radio .expense-radio:checked+.form-check-label {
                background-color: #ef4444;
                border-color: #ef4444;
            }

            .custom-input-group {
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                overflow: hidden;
            }

            .custom-input-group input {
                background-color: transparent;
                color: #0f172a;
            }

            .custom-select,
            .custom-input {
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 12px 15px;
                color: #0f172a;
            }

            /* Dark Mode Modal Overrides */
            body.dark-mode .custom-modal .modal-content {
                background-color: #1e293b;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            }

            body.dark-mode .custom-modal .modal-title {
                color: #f8fafc;
            }

            body.dark-mode .custom-modal .btn-close {
                filter: invert(1) grayscale(100%) brightness(200%);
            }

            body.dark-mode .custom-radio .form-check-label {
                border-color: #334155;
                color: #94a3b8;
            }

            body.dark-mode .custom-input-group,
            body.dark-mode .custom-select,
            body.dark-mode .custom-input {
                background-color: #0f172a;
                border-color: #334155;
                color: #f8fafc;
            }

            body.dark-mode .custom-input-group input {
                color: #f8fafc;
            }

            body.dark-mode .form-label.text-muted {
                color: #94a3b8 !important;
            }

            /* Transactions Table */
            .transactions-section {
                margin-top: 40px;
            }

            .transactions-title {
                font-size: 0.75rem;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                margin-bottom: 15px;
                letter-spacing: 0.5px;
            }

            .table-container {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 20px;
                overflow: hidden;
            }

            .custom-table {
                width: 100% !important;
                border-collapse: separate;
                border-spacing: 0 8px;
                margin-bottom: 0 !important;
            }

            .custom-table thead th {
                border: none !important;
                color: #64748b;
                font-size: 0.7rem;
                font-weight: 700;
                text-transform: uppercase;
                padding: 12px;
            }

            .custom-table tbody tr {
                background: #f8fafc;
                transition: transform 0.2s;
            }

            .custom-table tbody tr:hover {
                transform: scale(1.002);
            }

            .custom-table td {
                padding: 15px 12px;
                border: none !important;
                font-size: 0.85rem;
                vertical-align: middle;
            }

            .custom-table td:first-child {
                border-radius: 12px 0 0 12px;
            }

            .custom-table td:last-child {
                border-radius: 0 12px 12px 0;
            }

            .type-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
            }

            .type-income {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .type-expense {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
            }

            .category-badge {
                background-color: #f1f5f9;
                color: #475569;
                font-size: 0.65rem;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 6px;
                text-transform: uppercase;
            }

            /* Dark Mode Table */
            body.dark-mode .table-container {
                background: #1e293b;
                border-color: #334155;
            }

            body.dark-mode .custom-table tbody tr {
                background: #0f172a;
                color: #f8fafc;
            }

            body.dark-mode .custom-table thead th {
                color: #94a3b8;
            }

            body.dark-mode .category-badge {
                background-color: #334155;
                color: #cbd5e1;
            }

            /* DataTables Overrides */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 15px;
                font-weight: 600;
                font-size: 0.8rem;
                color: #64748b;
            }

            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 5px 10px;
            }

            body.dark-mode .dataTables_wrapper .dataTables_length,
            body.dark-mode .dataTables_wrapper .dataTables_filter {
                color: #94a3b8;
            }

            body.dark-mode .dataTables_wrapper .dataTables_filter input {
                background: #0f172a;
                border-color: #334155;
                color: white;
            }

            .table-responsive {
                border: none;
                -webkit-overflow-scrolling: touch;
            }

            @media (max-width: 768px) {
                .custom-table td {
                    min-width: 120px;
                }

                .custom-table td:first-child {
                    min-width: 150px;
                }
            }

            .custom-dropdown {
                border-radius: 12px;
                border: none;
                padding: 10px;
                background: #ffffff;
            }

            .custom-dropdown .dropdown-item {
                border-radius: 8px;
                font-size: 0.8rem;
                font-weight: 600;
                color: #64748b;
                padding: 8px 15px;
                transition: all 0.2s;
            }

            .custom-dropdown .dropdown-item:hover {
                background-color: #f1f5f9;
                color: #0f172a;
            }

            .custom-dropdown .dropdown-item.active {
                background-color: #3b82f6;
                color: white;
            }

            body.dark-mode .custom-dropdown {
                background: #1e293b;
                border: 1px solid #334155;
            }

            body.dark-mode .custom-dropdown .dropdown-item {
                color: #94a3b8;
            }

            body.dark-mode .custom-dropdown .dropdown-item:hover {
                background-color: #334155;
                color: white;
            }

            body.dark-mode .custom-dropdown .dropdown-header {
                color: #64748b;
            }
        </style>

        <div class="finance-container">
            <div class="finance-header">
                <h2 class="finance-title">CashFlow <span class="badge-records">Records</span></h2>
                <div class="dropdown">
                    <button class="btn-month dropdown-toggle border-0 shadow-none" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-calendar3"></i>
                        @if ($month == 'ALL')
                            ALL TIME
                        @else
                            {{ strtoupper(date('F', mktime(0, 0, 0, (int) $month, 10))) }} {{ $year }}
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown shadow border-0">
                        <li>
                            <h6 class="dropdown-header">Select Month ({{ $year }})</h6>
                        </li>
                        @foreach (range(1, 12) as $m)
                            @php $m_padded = sprintf('%02d', $m); @endphp
                            <li><a class="dropdown-item {{ $month == $m_padded ? 'active' : '' }}"
                                    href="{{ route('admin.finance.page', ['month' => $m_padded, 'year' => $year, 'method' => $method]) }}">
                                    {{ strtoupper(date('F', mktime(0, 0, 0, $m, 10))) }}
                                </a></li>
                        @endforeach
                        <li>
                            <hr class="dropdown-divider opacity-50">
                        </li>
                        <li><a class="dropdown-item {{ $month == 'ALL' ? 'active' : '' }}"
                                href="{{ route('admin.finance.page', ['month' => 'ALL', 'year' => $year, 'method' => $method]) }}">ALL
                                TIME</a></li>
                    </ul>
                </div>
            </div>

            <div class="filter-pills">
                <a href="{{ route('admin.finance.page', ['method' => 'ALL', 'month' => $month, 'year' => $year]) }}"
                    class="filter-pill {{ $method == 'ALL' ? 'active' : '' }}" style="text-decoration: none;">ALL</a>
                <a href="{{ route('admin.finance.page', ['method' => 'E-cash', 'month' => $month, 'year' => $year]) }}"
                    class="filter-pill {{ $method == 'E-cash' ? 'active' : '' }}"
                    style="text-decoration: none;">E-CASH</a>
                <a href="{{ route('admin.finance.page', ['method' => 'CASH', 'month' => $month, 'year' => $year]) }}"
                    class="filter-pill {{ $method == 'CASH' ? 'active' : '' }}" style="text-decoration: none;">CASH</a>
            </div>

            <div class="balance-section">
                <div class="balance-label">AVAILABLE BALANCE</div>
                <div class="balance-amount">₱{{ number_format($availableBalance ?? 0, 2) }}</div>
            </div>

            <div class="flow-cards">
                <div class="flow-card">
                    <div class="flow-label">TOTAL INFLOW</div>
                    <div class="flow-value text-inflow">₱{{ number_format($totalInflow ?? 0, 2) }}</div>
                </div>
                <div class="flow-card">
                    <div class="flow-label">TOTAL OUTFLOW</div>
                    <div class="flow-value text-outflow">₱{{ number_format($totalOutflow ?? 0, 2) }}</div>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="category-breakdown">
                <div class="category-label">CATEGORY BREAKDOWN</div>
                <div class="category-cards">
                    @forelse($categoryBreakdown as $category => $amount)
                        @php
                            $iconClass = 'bi-tags';
                            $iconColor = '#64748b';

                            switch (strtoupper($category)) {
                                case 'FOOD':
                                    $iconClass = 'bi-cup-hot';
                                    $iconColor = '#f59e0b';
                                    break;
                                case 'TRANSPORTATION':
                                    $iconClass = 'bi-car-front';
                                    $iconColor = '#3b82f6';
                                    break;
                                case 'SALES':
                                    $iconClass = 'bi-bag-check';
                                    $iconColor = '#10b981';
                                    break;
                                case 'ELECTRICITY':
                                    $iconClass = 'bi-lightning-charge';
                                    $iconColor = '#eab308';
                                    break;
                                case 'SALARY':
                                    $iconClass = 'bi-cash-stack';
                                    $iconColor = '#10b981';
                                    break;
                                case 'INTERNET':
                                    $iconClass = 'bi-wifi';
                                    $iconColor = '#8b5cf6';
                                    break;
                                case 'GROCERY/STOCKS':
                                    $iconClass = 'bi-cart3';
                                    $iconColor = '#f97316';
                                    break;
                            }
                        @endphp
                        <div class="category-card">
                            <div class="category-header">
                                <i class="bi {{ $iconClass }} category-icon"
                                    style="color: {{ $iconColor }}"></i>
                                <span class="category-name">{{ strtoupper($category) }}</span>
                            </div>
                            <div class="category-amount">₱{{ number_format($amount, 2) }}</div>
                        </div>
                    @empty
                        <div class="text-muted small fw-bold mt-2">No records available.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="transactions-section">
                <div class="transactions-title">TRANSACTIONS</div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table custom-table" id="financeTable">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="type-icon {{ $record->type == 'income' ? 'type-income' : 'type-expense' }}">
                                                    <i
                                                        class="bi {{ $record->type == 'income' ? 'bi-arrow-down-left' : 'bi-arrow-up-right' }}"></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ strtoupper($record->type) }}</span>
                                                    @if ($record->note)
                                                        <small class="text-muted"
                                                            style="font-size: 0.7rem; line-height: 1.2;">{{ $record->note }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $record->created_at->format('M d, Y h:i A') }}</td>
                                        <td><span class="category-badge">{{ $record->category }}</span></td>
                                        <td><small class="fw-bold text-muted">{{ $record->payment_method }}</small>
                                        </td>
                                        <td
                                            class="fw-bold {{ $record->type == 'income' ? 'text-inflow' : 'text-outflow' }}">
                                            {{ $record->type == 'income' ? '+' : '-' }}₱{{ number_format($record->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="finance-footer">
                <button class="btn-action btn-income" data-bs-toggle="modal" data-bs-target="#incomeModal">
                    <div class="icon-box"><i class="bi bi-plus-lg"></i></div>
                    <span>INCOME</span>
                </button>
                <button class="btn-action btn-expense" data-bs-toggle="modal" data-bs-target="#expenseModal">
                    <div class="icon-box"><i class="bi bi-dash-lg"></i></div>
                    <span>EXPENSE</span>
                </button>
            </div>
        </div>

        <!-- Income Modal -->
        <div class="modal fade custom-modal" id="incomeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">INCOME ENTRY</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.finance.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="income">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-uppercase small text-muted">Payment Type</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check custom-radio flex-fill p-0">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="incomeEcash" value="E-cash" checked>
                                        <label class="form-check-label w-100 text-center m-0"
                                            for="incomeEcash">E-cash</label>
                                    </div>
                                    <div class="form-check custom-radio flex-fill p-0">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="incomeCash" value="CASH">
                                        <label class="form-check-label w-100 text-center m-0"
                                            for="incomeCash">CASH</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-uppercase small text-muted">Amount</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text border-0 bg-transparent fw-bold fs-5">₱</span>
                                    <input type="number" class="form-control border-0 shadow-none fs-5 fw-bold"
                                        placeholder="0.00" step="0.01" name="amount" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-uppercase small text-muted">Category</label>
                                <select class="form-select custom-select shadow-none" name="category" required>
                                    <option value="" disabled selected>Select a category</option>
                                    <option value="Transportation">Transportation</option>
                                    <option value="Food">Food</option>
                                    <option value="Electricity">Electricity</option>
                                    <option value="Salary">Salary</option>
                                    <option value="Internet">Internet</option>
                                    <option value="Grocery/Stocks">Grocery/Stocks</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold text-uppercase small text-muted">Note
                                    (Optional)</label>
                                <textarea class="form-control custom-input shadow-none" rows="2" placeholder="Add a note..." name="note"></textarea>
                            </div>
                            <button type="submit" class="btn btn-action btn-income w-100 py-3 rounded-4"
                                style="border-radius: 12px !important;">SAVE INCOME</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Modal -->
        <div class="modal fade custom-modal" id="expenseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">EXPENSE ENTRY</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.finance.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="expense">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-uppercase small text-muted">Payment Type</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check custom-radio flex-fill p-0">
                                        <input class="form-check-input expense-radio" type="radio"
                                            name="payment_method" id="expenseEcash" value="E-cash" checked>
                                        <label class="form-check-label w-100 text-center m-0"
                                            for="expenseEcash">E-cash</label>
                                    </div>
                                    <div class="form-check custom-radio flex-fill p-0">
                                        <input class="form-check-input expense-radio" type="radio"
                                            name="payment_method" id="expenseCash" value="CASH">
                                        <label class="form-check-label w-100 text-center m-0"
                                            for="expenseCash">CASH</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-uppercase small text-muted">Amount</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text border-0 bg-transparent fw-bold fs-5">₱</span>
                                    <input type="number" class="form-control border-0 shadow-none fs-5 fw-bold"
                                        placeholder="0.00" step="0.01" name="amount" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-uppercase small text-muted">Category</label>
                                <select class="form-select custom-select shadow-none" name="category" required>
                                    <option value="" disabled selected>Select a category</option>
                                    <option value="Transportation">Transportation</option>
                                    <option value="Food">Food</option>
                                    <option value="Electricity">Electricity</option>
                                    <option value="Salary">Salary</option>
                                    <option value="Internet">Internet</option>
                                    <option value="Grocery/Stocks">Grocery/Stocks</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold text-uppercase small text-muted">Note
                                    (Optional)</label>
                                <textarea class="form-control custom-input shadow-none" rows="2" placeholder="Add a note..." name="note"></textarea>
                            </div>
                            <button type="submit" class="btn btn-action btn-expense w-100 py-3 rounded-4"
                                style="border-radius: 12px !important;">SAVE EXPENSE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top'
            },
            duration: 3000
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                notyf.success("{!! addslashes(session('success')) !!}");
            @endif

            @if (session('error'))
                notyf.error("{!! addslashes(session('error')) !!}");
            @endif
        });
    </script>

    <script src="{{ asset('assets/script.js') }}"></script>
    <script>
        const trainingTableElement = document.getElementById('trainingTable');
        let trainingDataTable;

        function initTrainingDataTable() {
            if (!trainingTableElement || !window.jQuery || !window.jQuery.fn.DataTable) {
                return;
            }

            if (trainingDataTable) {
                trainingDataTable.destroy();
            }

            trainingDataTable = window.jQuery(trainingTableElement).DataTable({
                pageLength: 10,
                order: [
                    [4, 'desc']
                ],
                autoWidth: false,
            });
        }


        initTrainingDataTable();

        // Initialize Finance DataTable
        $(document).ready(function() {
            $('#financeTable').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [1, 'desc']
                ], // Sort by date
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search transactions..."
                }
            });
        });
    </script>
</body>

</html>
