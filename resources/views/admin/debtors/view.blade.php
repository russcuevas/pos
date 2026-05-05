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
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <style>
        :root {
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --item-hover: rgba(0, 0, 0, 0.02);
            --accent-green: #10b981;
            --accent-blue: #3b82f6;
            --accent-red: #ef4444;
            --modal-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --receipt-bg: #ffffff;
            --receipt-text: #1e293b;
        }

        body.dark-mode {
            --card-bg: #1e293b;
            --card-border: #334155;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            --item-hover: rgba(255, 255, 255, 0.05);
            --modal-bg: #0f172a;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --bg-body: #0f172a;
            --receipt-bg: #1e293b;
            --receipt-text: #f1f5f9;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: background-color 0.3s, color 0.3s;
        }

        .main-content {
            background: transparent;
        }

        .detail-card {
            background: var(--card-bg);
            color: var(--text-main);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            border: 1px solid var(--card-border);
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
        }

        .outstanding-label {
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .outstanding-value {
            font-size: 48px;
            font-weight: 900;
            color: var(--accent-green);
            margin-bottom: 25px;
            letter-spacing: -1px;
        }

        .stat-grid {
            display: flex;
            justify-content: center;
            gap: 50px;
            border-top: 1px solid var(--card-border);
            padding-top: 25px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 5px;
        }

        .stat-val {
            font-weight: 800;
            font-size: 18px;
            color: var(--text-main);
        }

        .stat-val.paid {
            color: var(--accent-blue);
        }

        .stat-val.profit {
            color: var(--accent-green);
        }

        .stat-val.credit {
            color: var(--accent-red);
        }

        .activity-timeline {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-wrapper {
            background: var(--card-bg);
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--card-border);
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .activity-wrapper:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--accent-blue);
        }

        .activity-item {
            background: transparent;
            color: var(--text-main);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }

        .activity-item:hover {
            background: var(--item-hover);
        }

        .text-green {
            color: var(--accent-green) !important;
        }

        .text-red {
            color: var(--accent-red) !important;
        }

        .text-blue {
            color: var(--accent-blue) !important;
        }

        .activity-icon-wrap {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 18px;
            flex-shrink: 0;
        }

        .icon-bg-danger {
            background: rgba(239, 68, 68, 0.12);
            color: var(--accent-red);
        }

        .icon-bg-success {
            background: rgba(16, 185, 129, 0.12);
            color: var(--accent-green);
        }

        .activity-info {
            flex: 1;
            text-align: left;
        }

        .activity-title {
            font-weight: 700;
            margin: 0;
            font-size: 15px;
            color: var(--text-main);
        }

        .activity-time {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .activity-amount {
            text-align: right;
        }

        .amount-main {
            font-weight: 800;
            font-size: 18px;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .amount-sub {
            font-size: 12px;
            font-weight: 700;
            margin-top: 2px;
        }

        .activity-details {
            border-top: 1px dashed var(--card-border);
            background: var(--bg-body);
            padding: 25px;
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 10px 15px;
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--card-border);
        }

        .detail-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        .detail-price-wrap {
            text-align: right;
        }

        .detail-price {
            font-weight: 800;
            font-size: 15px;
            color: var(--text-main);
        }

        .detail-profit {
            font-size: 11px;
            font-weight: 700;
            margin-top: 1px;
        }

        .details-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 15px;
            border-top: 1px solid var(--card-border);
        }

        .btn-detail {
            font-size: 12px;
            font-weight: 800;
            padding: 8px 18px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-detail-print {
            color: white;
            background: var(--accent-blue);
            border: none;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-detail-return {
            color: white;
            background: var(--accent-red);
            border: none;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-detail:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: var(--sidebar-w, 260px);
            right: 0;
            padding: 25px;
            border-top: 1px solid var(--card-border);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            display: flex;
            justify-content: center;
            gap: 20px;
            z-index: 1000;
            transition: all 0.3s;
        }

        body.dark-mode .sticky-footer {
            background: rgba(15, 23, 42, 0.85);
        }

        body:not(.dark-mode) .sticky-footer {
            background: rgba(255, 255, 255, 0.85);
        }

        body.sidebar-mini .sticky-footer {
            left: var(--sidebar-mini-w, 80px);
        }

        .btn-action {
            padding: 14px 30px;
            border-radius: 16px;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            min-width: 160px;
            justify-content: center;
            color: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-payment {
            background: var(--accent-green);
        }

        .btn-items {
            background: var(--accent-red);
        }

        .btn-note {
            background: #64748b;
        }

        .btn-action:hover {
            transform: translateY(-4px) scale(1.02);
            filter: brightness(1.1);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        /* Modal Styles */
        .modal-content {
            background: var(--modal-bg);
            color: var(--text-main);
            border: 1px solid var(--card-border);
            box-shadow: var(--card-shadow);
            border-radius: 24px;
        }

        .product-search-wrap {
            position: relative;
            margin-bottom: 20px;
        }

        .product-search-wrap i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .product-search-input {
            padding-left: 45px;
            border-radius: 14px;
            border: 1px solid var(--card-border);
            background: var(--bg-body);
            color: var(--text-main);
            height: 50px;
        }

        .product-list-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: var(--bg-body);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .product-item:hover {
            background: var(--card-bg);
            border-color: var(--accent-blue);
            transform: translateX(5px);
        }

        .product-img {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 15px;
        }

        .product-name {
            font-weight: 700;
            font-size: 15px;
            margin: 0;
            color: var(--text-main);
        }

        .product-price {
            font-weight: 800;
            font-size: 14px;
            color: var(--accent-blue);
            margin: 0;
        }

        .cart-item-row {
            background: var(--bg-body);
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid var(--card-border);
            transition: all 0.2s;
        }

        .btn-custom-item {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            background: var(--item-hover);
            border: 1.5px dashed var(--card-border);
            color: var(--text-main);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .btn-custom-item:hover {
            background: var(--card-border);
            border-color: var(--text-muted);
        }

        .form-control {
            background: var(--bg-body);
            border-color: var(--card-border);
            color: var(--text-main);
            border-radius: 12px;
        }

        .form-control:focus {
            background: var(--card-bg);
            color: var(--text-main);
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .qty-modal-content {
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 24px;
        }

        .qty-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .qty-btn {
            background: #1e293b;
            border: 1px solid #334155;
            color: #38bdf8;
            padding: 15px 5px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.2s;
        }

        .qty-btn:hover {
            background: #334155;
            color: white;
        }

        .qty-input-wrap {
            display: flex;
            background: #1e293b;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #334155;
        }

        .qty-input {
            background: transparent;
            border: none;
            color: white !important;
            padding: 12px 15px;
            flex: 1;
            font-size: 18px;
            font-weight: 600;
        }

        .qty-input:focus {
            outline: none;
            box-shadow: none;
        }

        .qty-set-btn {
            background: #10b981;
            border: none;
            color: white;
            padding: 12px 15px;
            font-weight: 800;
            font-size: 18px;
            transition: all 0.2s;
        }

        .qty-set-btn:hover {
            filter: brightness(1.1);
        }

        .qty-label {
            color: #64748b;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        /* Payment Modal Specific Styles */
        .payment-tabs {
            display: flex;
            background: var(--bg-body);
            padding: 5px;
            border-radius: 16px;
            margin-bottom: 25px;
            border: 1px solid var(--card-border);
        }

        .payment-tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            font-weight: 800;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.2s;
            color: var(--text-muted);
        }

        .payment-tab.active {
            background: var(--card-bg);
            color: var(--accent-blue);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .payment-amount-box {
            background: var(--bg-body);
            border: 2px solid var(--card-border);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            margin-bottom: 25px;
            transition: all 0.3s;
        }

        .payment-amount-box:focus-within {
            border-color: var(--accent-green);
            background: var(--card-bg);
        }

        .payment-amount-input {
            background: transparent;
            border: none;
            width: 100%;
            font-size: 42px;
            font-weight: 900;
            color: var(--text-main);
            text-align: center;
            outline: none;
        }

        .payment-amount-input::placeholder {
            color: var(--text-muted);
            opacity: 0.3;
        }

        .payment-note-area {
            width: 100%;
            background: var(--bg-body);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 15px;
            color: var(--text-main);
            font-size: 14px;
            resize: none;
            min-height: 100px;
            margin-bottom: 20px;
        }

        .payment-note-area:focus {
            border-color: var(--accent-blue);
            background: var(--card-bg);
            outline: none;
        }

        @media (max-width: 768px) {
            .sticky-footer {
                left: 0 !important;
                padding: 15px 10px;
                gap: 10px;
            }

            .btn-action {
                min-width: auto;
                flex: 1;
                padding: 12px 10px;
                font-size: 12px;
                border-radius: 12px;
            }
        }

        /* Receipt Modal Styles */
        .receipt-modal-content {
            background: var(--receipt-bg);
            color: var(--receipt-text);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            width: 380px;
            margin: 0 auto;
            font-family: 'Inter', sans-serif;
            box-shadow: var(--card-shadow);
        }

        .receipt-paper {
            padding: 40px 30px;
            border-radius: 24px;
        }

        body.dark-mode .receipt-paper {
            background: #1e293b;
        }

        body:not(.dark-mode) .receipt-paper {
            background: #ffffff;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .receipt-store-name {
            font-size: 26px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1.5px;
            color: var(--text-main);
        }

        .receipt-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 5px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .receipt-meta {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 20px;
            line-height: 1.8;
            font-weight: 500;
        }

        .receipt-divider {
            border-top: 1.5px dashed var(--card-border);
            margin: 20px 0;
        }

        .receipt-item {
            margin-bottom: 15px;
        }

        .receipt-item-top {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 15px;
            color: var(--text-main);
        }

        .receipt-item-bottom {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 3px;
        }

        .receipt-total-row {
            display: flex;
            justify-content: space-between;
            font-weight: 900;
            font-size: 18px;
            margin-top: 20px;
            color: var(--text-main);
        }

        .receipt-thanks {
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 30px;
            font-style: italic;
        }

        .receipt-actions {
            display: flex;
            gap: 12px;
            padding: 0 30px 30px 30px;
        }

        .receipt-btn-close {
            flex: 1;
            background: var(--bg-body);
            color: var(--text-main);
            border: 1px solid var(--card-border);
            padding: 14px;
            border-radius: 16px;
            font-weight: 800;
            transition: all 0.2s;
        }

        .receipt-btn-print {
            flex: 1;
            background: var(--accent-blue);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 16px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .receipt-btn-print:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4);
        }

        @media (max-width: 450px) {
            .receipt-modal-content {
                width: 100%;
                border-radius: 0;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #receiptModal,
            #receiptModal * {
                visibility: visible;
            }

            #receiptModal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .receipt-actions,
            .btn-close {
                display: none !important;
            }

            .modal-backdrop {
                display: none !important;
            }

            .receipt-paper {
                box-shadow: none;
                border: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>

    @include('admin.components.left_sidebar')
    @include('admin.components.navbar')

    <main class="main-content" style="padding-bottom: 100px;">

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('admin.debtors.page') }}" class="btn btn-sm btn-light rounded-pill mb-2"
                    aria-label="Go back to the debtors list">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Debtors
                </a>
                <h2 id="page-title">{{ $debtor->customer_name }}'s Account</h2>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="detail-card">
            <p class="outstanding-label">Total Outstanding Debt</p>
            <p class="outstanding-value">&#8369;{{ number_format($debtor->balance, 2) }}</p>

            <div class="stat-grid">
                <div class="stat-item">
                    <p class="stat-label">Total Credit</p>
                    <p class="stat-val credit">&#8369;{{ number_format($totalCredit, 2) }}</p>
                </div>
                <div class="stat-item">
                    <p class="stat-label">Total Paid</p>
                    <p class="stat-val paid">&#8369;{{ number_format($totalPaid, 2) }}</p>
                </div>
                <div class="stat-item">
                    <p class="stat-label">Total Profit <i class="bi bi-info-circle" style="font-size: 10px;"></i></p>
                    <p class="stat-val profit">&#8369;{{ number_format($totalProfit, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="activity-timeline">
            @forelse ($activity as $item)
                <div class="activity-wrapper">
                    <div class="activity-item" onclick="toggleDetails(this)">
                        <div class="d-flex align-items-center">
                            <div
                                class="activity-icon-wrap icon-bg-{{ $item['badge_color'] == 'red' ? 'danger' : 'success' }}">
                                <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i>
                            </div>
                            <div class="activity-info">
                                <p class="activity-title">{{ $item['title'] }}</p>
                                <p class="activity-time">
                                    {{ $item['created_at']->format('n/j/Y g:i:s A') }}
                                    @if (isset($item['items_count']))
                                        <span class="badge bg-dark text-white border ms-1 fw-normal"
                                            style="font-size: 10px;">{{ $item['items_count'] }} items</span>
                                    @endif
                                </p>
                                @if ($item['type'] == 'payment' && !empty($item['note']))
                                    <p class="small text-muted mb-0 mt-1" style="font-size: 11px;">
                                        <i class="bi bi-hash"></i> {{ $item['note'] }}
                                    </p>
                                @endif
                                @if ($item['type'] == 'debt' && !empty($item['batch_number']))
                                    <p class="small text-muted mb-0 mt-1" style="font-size: 11px;">
                                        <i class="bi bi-hash"></i> {{ $item['batch_number'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="activity-amount">
                            <p class="amount-main text-{{ $item['badge_color'] }}">
                                {{ $item['type'] == 'debt' ? '+' : '-' }}&#8369;{{ number_format($item['amount'], 2) }}
                            </p>
                            @if (isset($item['profit']))
                                <p class="amount-sub text-green">+&#8369;{{ number_format($item['profit'], 2) }}</p>
                            @endif
                        </div>
                    </div>

                    @if ($item['type'] == 'debt' && isset($item['batch_items']))
                        <div class="activity-details">
                            @foreach ($item['batch_items'] as $bItem)
                                <div class="detail-row">
                                    <div class="detail-name">
                                        {{ (float) $bItem->quantity }}x {{ $bItem->title }}
                                    </div>
                                    <div class="detail-price-wrap">
                                        <div class="detail-price">
                                            ₱{{ number_format($bItem->selling_price * $bItem->quantity, 2) }}</div>
                                        <div class="detail-profit text-green">
                                            +₱{{ number_format(($bItem->selling_price - $bItem->supplier_price) * $bItem->quantity, 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="details-footer">
                                <button class="btn-detail btn-detail-print open-receipt-modal"
                                    data-debt-id="{{ $item['batch_items']->first()->batch_number ?? 'N/A' }}"
                                    data-date="{{ $item['created_at']->format('n/j/Y') }}"
                                    data-time="{{ $item['created_at']->format('g:i A') }}"
                                    data-customer="{{ $debtor->customer_name }}"
                                    data-items="{{ json_encode(
                                        $item['batch_items']->map(function ($bi) {
                                            return [
                                                'title' => $bi->title,
                                                'quantity' => (float) $bi->quantity,
                                                'price' => (float) $bi->selling_price,
                                            ];
                                        }),
                                    ) }}">
                                    <i class="bi bi-printer"></i> Print
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center p-5 text-muted">
                    <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.2;"></i>
                    <p class="mt-2">No activity recorded yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Sticky Footer -->
        <div class="sticky-footer" role="group" aria-label="Account Actions">
            <button class="btn-action btn-payment" data-bs-toggle="modal" data-bs-target="#paymentModal"
                aria-label="Make a payment for this account">
                <i class="bi bi-cash-stack" aria-hidden="true"></i> Payment
            </button>
            <button class="btn-action btn-items" data-bs-toggle="modal" data-bs-target="#addItemsModal"
                aria-label="Add items to this account's debt">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Items
            </button>
        </div>

        <!-- Add Items Modal -->
        <div class="modal fade" id="addItemsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 20px;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Add Items to Debt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <!-- Left Side: Product Search -->
                            <div class="col-md-7 border-end">
                                <div class="product-search-wrap">
                                    <input type="text" class="form-control product-search-input"
                                        id="productSearch" placeholder="Search products...">
                                </div>

                                <div class="product-list-container" id="productList">
                                    @foreach ($products as $product)
                                        <div class="product-item"
                                            data-name="{{ strtolower($product->product_name) }}"
                                            data-id="{{ $product->id }}"
                                            data-price="{{ $product->selling_price }}">
                                            <img src="{{ asset('images/products/' . $product->product_image) }}"
                                                class="product-img" alt="{{ $product->product_name }}">
                                            <div class="product-info">
                                                <p class="product-name">{{ $product->product_name }}</p>
                                                <p class="product-price">
                                                    &#8369;{{ number_format($product->selling_price, 2) }}
                                                </p>
                                            </div>
                                            <i class="bi bi-plus-circle-fill text-primary ms-auto"
                                                style="font-size: 24px;"></i>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn-custom-item" id="openCustomItemBtn">
                                    <i class="bi bi-pencil-square"></i> Add Custom Item
                                </button>
                            </div>

                            <!-- Right Side: Selected Items -->
                            <div class="col-md-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="fw-bold m-0 text-muted small">SELECTED ITEMS</p>
                                    <span class="badge rounded-pill bg-primary" id="itemsCountBadge">0</span>
                                </div>

                                <div id="selectedItemsList" style="max-height: 350px; overflow-y: auto;">
                                    <div class="text-center py-5 text-muted" id="noItemsPlaceholder">
                                        <i class="bi bi-cart-x d-block mb-2"
                                            style="font-size: 2rem; opacity: 0.3;"></i>
                                        <p class="small">No items selected</p>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted small fw-bold">TOTAL AMOUNT</span>
                                        <span class="fw-bold h5 mb-0 text-primary" id="batchTotalDisplay">₱0.00</span>
                                    </div>
                                    <button class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-sm"
                                        id="confirmAddBatch" disabled>
                                        Confirm & Add to Debt
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Item Modal (Sm) -->
        <div class="modal fade" id="customItemModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content" style="border-radius: 20px;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Create Custom Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Item Name</label>
                            <input type="text" id="customItemName" class="form-control rounded-3"
                                placeholder="Enter item name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Price / Amount</label>
                            <input type="number" id="customItemPrice" step="0.01" class="form-control rounded-3"
                                placeholder="0.00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Cost (Optional)</label>
                            <input type="number" id="customItemCost" step="0.01" class="form-control rounded-3"
                                placeholder="0.00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Quantity</label>
                            <input type="number" id="customItemQty" class="form-control rounded-3" value="1"
                                min="0.1" step="0.1">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success rounded-pill px-4 fw-bold"
                            id="addCustomItemToList">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quantity Selector Modal -->
        <div class="modal fade" id="quantityModal" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
            <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
                <div class="modal-content qty-modal-content">
                    <div class="modal-body p-4">
                        <h5 class="text-center fw-bold mb-4">Set Quantity</h5>

                        <div class="qty-grid">
                            <button type="button" class="qty-btn" data-val="0.25">1/4</button>
                            <button type="button" class="qty-btn" data-val="0.33">1/3</button>
                            <button type="button" class="qty-btn" data-val="0.5">1/2</button>
                            <button type="button" class="qty-btn" data-val="0.75">3/4</button>
                            <button type="button" class="qty-btn" data-val="1">1</button>
                            <button type="button" class="qty-btn" data-val="2">2</button>
                            <button type="button" class="qty-btn" data-val="3">3</button>
                            <button type="button" class="qty-btn" data-val="4">4</button>
                            <button type="button" class="qty-btn" data-val="5">5</button>
                            <button type="button" class="qty-btn" data-val="10">10</button>
                            <button type="button" class="qty-btn" data-val="15">15</button>
                            <button type="button" class="qty-btn" data-val="20">20</button>
                        </div>

                        <div class="qty-label">CUSTOM QUANTITY</div>
                        <div class="qty-input-wrap">
                            <input type="number" id="manualQty" class="qty-input" value="1" step="0.01">
                            <button type="button" class="qty-set-btn" id="setQtyBtn">Set</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content receipt-modal-content">
                    <div class="modal-body p-0">
                        <div class="receipt-paper">
                            <div class="receipt-header">
                                <h1 class="receipt-store-name">SAMMER'S STORE</h1>
                                <p class="receipt-subtitle">Debt Details</p>
                            </div>

                            <div class="receipt-meta">
                                <div class="d-flex justify-content-between">
                                    <span id="receiptDate">5/5/2026</span>
                                    <span id="receiptTime">10:02 PM</span>
                                </div>
                                <div id="receiptDebtNo">Debt #: 27349</div>
                                <div id="receiptCustomer">Customer: TTTest</div>
                            </div>

                            <div class="receipt-divider"></div>

                            <div id="receiptItemsList">
                                <!-- Items will be injected here -->
                            </div>

                            <div class="receipt-divider"></div>

                            <div class="receipt-total-row">
                                <span>Total Debt Added</span>
                                <span id="receiptTotalAmount">₱26.00</span>
                            </div>

                            <div class="receipt-thanks">Thank you!</div>
                        </div>

                        <div class="receipt-actions px-3 pb-4">
                            <button class="receipt-btn-close" data-bs-dismiss="modal">Close</button>
                            <button class="receipt-btn-print" onclick="window.print()">
                                <i class="bi bi-printer"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content p-2" style="border-radius: 28px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Record Payment for {{ $debtor->customer_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Payment Type Tabs -->
                        <div class="payment-tabs">
                            <div class="payment-tab active" data-type="Cash">Cash</div>
                            <div class="payment-tab" data-type="E-Cash">E-Cash</div>
                        </div>

                        <div class="qty-label mb-2">Payment Amount</div>
                        <div class="payment-amount-box">
                            <input type="number" id="paymentAmountInput" class="payment-amount-input"
                                placeholder="0.00" step="0.01">
                        </div>

                        <textarea id="paymentNote" class="payment-note-area" placeholder="Reference No. of the Added Debt"></textarea>

                        <div class="d-flex gap-3 mt-2">
                            <button class="btn btn-light flex-grow-1 rounded-pill py-3 fw-bold"
                                data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary flex-grow-1 rounded-pill py-3 fw-bold"
                                id="confirmPaymentBtn">Save Payment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    <script>
        function toggleDetails(element) {
            const details = element.nextElementSibling;
            if (details && details.classList.contains('activity-details')) {
                const isVisible = details.style.display === 'block';
                details.style.display = isVisible ? 'none' : 'block';

                // Optional: Highlight the active item
                element.parentElement.style.borderColor = isVisible ? 'var(--border)' : '#3b82f6';
                element.parentElement.style.boxShadow = isVisible ? 'var(--shadow-sm)' : 'var(--shadow-md)';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            let selectedItems = [];

            const productSearch = document.getElementById('productSearch');
            const productItems = document.querySelectorAll('.product-item');
            const selectedItemsList = document.getElementById('selectedItemsList');
            const noItemsPlaceholder = document.getElementById('noItemsPlaceholder');
            const itemsCountBadge = document.getElementById('itemsCountBadge');
            const batchTotalDisplay = document.getElementById('batchTotalDisplay');
            const confirmAddBatch = document.getElementById('confirmAddBatch');

            // Modals
            const addItemsModal = new bootstrap.Modal(document.getElementById('addItemsModal'));
            const customItemModal = new bootstrap.Modal(document.getElementById('customItemModal'));
            const quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));

            let currentProduct = null;

            // Search filtering
            productSearch.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                productItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    item.style.display = name.includes(query) ? 'flex' : 'none';
                });
            });

            // Handle product selection - Open Quantity Modal
            productItems.forEach(item => {
                item.addEventListener('click', function() {
                    currentProduct = {
                        products_id: this.getAttribute('data-id'),
                        title: this.querySelector('.product-name').textContent,
                        price: parseFloat(this.getAttribute('data-price'))
                    };

                    document.getElementById('manualQty').value = '1';
                    quantityModal.show();
                });
            });

            // Quantity Modal Presets
            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('manualQty').value = this.getAttribute('data-val');
                });
            });

            // Set Quantity and add to batch
            document.getElementById('setQtyBtn').addEventListener('click', function() {
                const qty = parseFloat(document.getElementById('manualQty').value);
                if (currentProduct && !isNaN(qty) && qty > 0) {
                    addItemToBatch({
                        ...currentProduct,
                        quantity: qty
                    });
                    quantityModal.hide();
                }
            });

            // Open Custom Item Modal
            document.getElementById('openCustomItemBtn').addEventListener('click', () => {
                // Not hiding addItemsModal as per request
                customItemModal.show();
            });

            // Add Custom Item to list
            document.getElementById('addCustomItemToList').addEventListener('click', () => {
                const name = document.getElementById('customItemName').value;
                const price = parseFloat(document.getElementById('customItemPrice').value);
                const cost = parseFloat(document.getElementById('customItemCost').value) || null;
                const qty = parseFloat(document.getElementById('customItemQty').value);

                if (name && !isNaN(price) && !isNaN(qty)) {
                    addItemToBatch({
                        custom_entry: name,
                        title: name,
                        price: price,
                        cost: cost,
                        quantity: qty
                    });

                    // Reset and return
                    document.getElementById('customItemName').value = '';
                    document.getElementById('customItemPrice').value = '';
                    document.getElementById('customItemCost').value = '';
                    document.getElementById('customItemQty').value = '1';

                    customItemModal.hide();
                }
            });

            function addItemToBatch(item) {
                // Check if product already in batch
                if (item.products_id) {
                    const existing = selectedItems.find(i => i.products_id === item.products_id);
                    if (existing) {
                        existing.quantity += item.quantity;
                        updateBatchUI();
                        return;
                    }
                }

                selectedItems.push(item);
                updateBatchUI();
            }

            function updateBatchUI() {
                if (selectedItems.length === 0) {
                    noItemsPlaceholder.style.display = 'block';
                    selectedItemsList.innerHTML = '';
                    selectedItemsList.appendChild(noItemsPlaceholder);
                    confirmAddBatch.disabled = true;
                    batchTotalDisplay.textContent = '₱0.00';
                } else {
                    noItemsPlaceholder.style.display = 'none';
                    selectedItemsList.innerHTML = '';

                    let total = 0;

                    selectedItems.forEach((item, index) => {
                        const itemTotal = item.price * item.quantity;
                        total += itemTotal;

                        const row = document.createElement('div');
                        row.className = 'cart-item-row d-flex align-items-center justify-content-between';
                        row.innerHTML = `
                            <div class="activity-info">
                                <p class="activity-title" style="font-size: 13px;">${item.title}</p>
                                <p class="activity-time">₱${item.price.toLocaleString()} x ${item.quantity}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="fw-bold small">₱${itemTotal.toLocaleString()}</div>
                                <button class="btn btn-sm text-danger p-0" onclick="removeItemFromBatch(${index})" aria-label="Remove item">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>
                        `;
                        selectedItemsList.appendChild(row);
                    });

                    batchTotalDisplay.textContent =
                        `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                    confirmAddBatch.disabled = false;
                }

                itemsCountBadge.textContent = selectedItems.length;
            }

            window.removeItemFromBatch = (index) => {
                selectedItems.splice(index, 1);
                updateBatchUI();
            };

            // Confirm Batch Submission
            confirmAddBatch.addEventListener('click', async function() {
                this.disabled = true;
                this.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

                try {
                    const response = await fetch(
                        "{{ route('admin.debtors.add_batch', $debtor->id) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                items: selectedItems.map(item => ({
                                    products_id: item.products_id || null,
                                    custom_entry: item.custom_entry || null,
                                    custom_price: item.custom_entry ? item
                                        .price : null,
                                    custom_cost: item.custom_entry ? item.cost :
                                        null,
                                    quantity: item.quantity
                                }))
                            })
                        });

                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Something went wrong!');
                        this.disabled = false;
                        this.textContent = 'Confirm & Add to Debt';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Network error!');
                    this.disabled = false;
                    this.textContent = 'Confirm & Add to Debt';
                }
            });

            // Handle Receipt Modal Opening
            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            document.querySelectorAll('.open-receipt-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = {
                        debtId: this.getAttribute('data-debt-id'),
                        date: this.getAttribute('data-date'),
                        time: this.getAttribute('data-time'),
                        customer: this.getAttribute('data-customer'),
                        items: JSON.parse(this.getAttribute('data-items'))
                    };

                    document.getElementById('receiptDate').textContent = data.date;
                    document.getElementById('receiptTime').textContent = data.time;
                    document.getElementById('receiptDebtNo').textContent = `Debt #: ${data.debtId}`;
                    document.getElementById('receiptCustomer').textContent =
                        `Customer: ${data.customer}`;

                    const list = document.getElementById('receiptItemsList');
                    list.innerHTML = '';
                    let total = 0;

                    data.items.forEach(item => {
                        const itemTotal = item.price * item.quantity;
                        total += itemTotal;

                        const row = document.createElement('div');
                        row.className = 'receipt-item';
                        row.innerHTML = `
                            <div class="receipt-item-top">
                                <span>${item.title}</span>
                                <span>₱${itemTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                            <div class="receipt-item-bottom">
                                ${item.quantity} x ₱${item.price.toLocaleString(undefined, {minimumFractionDigits: 2})}
                            </div>
                        `;
                        list.appendChild(row);
                    });

                    document.getElementById('receiptTotalAmount').textContent =
                        `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                    receiptModal.show();
                });
            });

            // --- Payment Modal Logic ---
            let selectedPaymentType = 'Cash';
            const paymentTabs = document.querySelectorAll('.payment-tab');
            const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');

            paymentTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    paymentTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    selectedPaymentType = this.getAttribute('data-type');
                });
            });

            confirmPaymentBtn.addEventListener('click', async function() {
                const amount = parseFloat(document.getElementById('paymentAmountInput').value);
                const note = document.getElementById('paymentNote').value;

                if (isNaN(amount) || amount <= 0) {
                    alert('Please enter a valid payment amount.');
                    return;
                }

                this.disabled = true;
                this.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                try {
                    const response = await fetch(
                        "{{ route('admin.debtors.add_payment', $debtor->id) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                payment_amount: amount,
                                payment_type: selectedPaymentType,
                                note: note
                            })
                        });

                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Something went wrong!');
                        this.disabled = false;
                        this.textContent = 'Save Payment';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Network error!');
                    this.disabled = false;
                    this.textContent = 'Save Payment';
                }
            });
        });
    </script>
</body>

</html>
