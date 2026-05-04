<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Barlow+Condensed:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/pos.css') }}">
    <style>
        .orders-container {
            flex: 1;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 30px 40px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        body.pos-page {
            overflow-y: auto !important;
            height: auto !important;
        }

        .order-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            border: 1px solid #edf2f7;
            overflow: hidden;
        }

        .dark-mode .order-card {
            background: #111827;
            border-color: #1f2937;
        }

        .cancelled-order {
            opacity: 0.6;
            filter: grayscale(0.6);
            cursor: not-allowed;
        }

        .cancelled-order .item-name,
        .cancelled-order .order-number,
        .cancelled-order .customer-info {
            text-decoration: line-through;
        }

        .order-card-header {
            padding: 15px 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .order-number {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a202c;
        }

        .dark-mode .order-number {
            color: #f7fafc;
        }

        .order-date {
            font-size: 0.75rem;
            color: #718096;
            margin-top: 4px;
        }

        .customer-info {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1a202c;
            margin-top: 5px;
        }

        .dark-mode .customer-info {
            color: #cbd5e1;
        }

        .order-card-body {
            padding: 0 24px 15px 24px;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-top: 1px solid #f1f5f9;
        }

        .dark-mode .order-item-row {
            border-top-color: #1f2937;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .item-name {
            color: #4a5568;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .dark-mode .item-name {
            color: #a0aec0;
        }

        .item-price {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
        }

        .dark-mode .item-price {
            color: #e2e8f0;
        }

        .order-card-footer {
            padding: 10px 24px;
            background: #fff;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark-mode .order-card-footer {
            background: #111827;
            border-top-color: #1f2937;
        }

        .payment-summary-row {
            padding: 10px 24px;
            background: #fafafa;
            margin: 0 -24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
        }

        .payment-summary-row.main {
            border-top: 2px solid #f1f5f9;
        }

        .dark-mode .payment-summary-row {
            background: #1f2937;
            border-top-color: #374151;
        }

        .order-note-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f0;
            font-size: 0.85rem;
            color: #718096;
        }

        .dark-mode .order-note-section {
            border-top-color: #374151;
            color: #94a3b8;
        }

        /* Receipt Modal Styling */
        .receipt-modal-content {
            font-family: 'Inter', sans-serif;
            padding: 20px;
            color: #1a202c;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            margin-bottom: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .receipt-header p {
            font-size: 0.85rem;
            color: #718096;
            margin: 0;
        }

        .receipt-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: #4a5568;
            margin-bottom: 15px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 10px;
        }

        .receipt-items {
            margin-bottom: 15px;
        }

        .receipt-item-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .receipt-item-name {
            font-weight: 700;
            color: #1a202c;
        }

        .receipt-item-qty {
            font-size: 0.75rem;
            color: #718096;
            display: block;
        }

        .receipt-summary {
            border-top: 1px dashed #e2e8f0;
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .summary-row.total {
            font-weight: 800;
            font-size: 1.1rem;
            margin-top: 5px;
            padding-top: 5px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 0.75rem;
            color: #a0aec0;
            font-style: italic;
        }

        .dark-mode .receipt-modal-content {
            color: #f7fafc;
        }

        .dark-mode .receipt-item-name {
            color: #f7fafc;
        }

        .dark-mode .receipt-header p,
        .dark-mode .receipt-info,
        .dark-mode .receipt-item-qty {
            color: #a0aec0;
        }

        .dark-mode .receipt-info,
        .dark-mode .receipt-summary {
            border-top-color: #374151;
            border-bottom-color: #374151;
        }

        /* Return Modal Styling */
        .return-modal-content {
            padding: 20px;
        }

        .return-info-alert {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 12px 16px;
            color: #0369a1;
            font-size: 0.85rem;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .return-item-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }

        .return-item-card:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .return-item-card.selected {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .return-item-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .return-item-details {
            flex: 1;
        }

        .return-item-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .return-item-meta {
            font-size: 0.75rem;
            color: #64748b;
        }

        .return-checkbox-wrapper {
            width: 24px;
            height: 24px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .selected .return-checkbox-wrapper {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .return-checkbox-wrapper i {
            color: white;
            font-size: 0.8rem;
            display: none;
        }

        .selected .return-checkbox-wrapper i {
            display: block;
        }

        .return-modal-footer {
            padding: 20px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark-mode .return-info-alert {
            background: rgba(3, 105, 161, 0.1);
            border-color: #0369a1;
            color: #7dd3fc;
        }

        .dark-mode .return-item-card {
            background: #111827;
            border-color: #1f2937;
        }

        .dark-mode .return-item-card:hover {
            border-color: #3b82f6;
            background: #1e293b;
        }

        .dark-mode .return-item-card.selected {
            background: #1e3a8a;
            border-color: #3b82f6;
        }

        .dark-mode .return-item-name {
            color: #f1f5f9;
        }

        .dark-mode .return-modal-footer {
            border-top-color: #1f2937;
        }

        .return-refund-details {
            display: none;
            margin-top: 0;
            padding-top: 15px;
            border-top: 1px solid #e0f2fe;
            height: auto;
        }

        .selected .return-refund-details {
            display: flex;
            flex-direction: column;
        }

        /* Return Steps */
        .return-step {
            display: none;
        }

        .return-step.active {
            display: block;
        }

        .refund-source-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .source-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .source-card:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .source-card.active {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .source-card i {
            font-size: 1.5rem;
            color: #64748b;
            margin-bottom: 10px;
            display: block;
        }

        .source-card.active i {
            color: #3b82f6;
        }

        .source-card span {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
        }

        .return-summary-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .warning-alert {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 8px;
            padding: 12px 16px;
            color: #92400e;
            font-size: 0.85rem;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .dark-mode .source-card,
        .dark-mode .return-summary-card {
            background: #111827;
            border-color: #1f2937;
        }

        .dark-mode .source-card:hover {
            background: #1e293b;
        }

        .dark-mode .source-card.active {
            background: #1e3a8a;
        }

        .dark-mode .source-card span {
            color: #cbd5e1;
        }

        .dark-mode .summary-item {
            border-bottom-color: #1f2937;
        }

        .dark-mode .warning-alert {
            background: rgba(146, 64, 14, 0.1);
            border-color: #92400e;
            color: #fcd34d;
        }

        .dark-mode .dark-mode-text-white {
            color: #f1f5f9 !important;
        }

        .return-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .return-input-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            width: 100px;
        }

        .return-input-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }

        .return-input-prefix {
            background: #f8fafc;
            padding: 5px 10px;
            border-right: 1px solid #cbd5e1;
            font-size: 0.85rem;
            color: #64748b;
        }

        .return-input {
            border: none;
            padding: 5px 12px;
            width: 100%;
            font-weight: 700;
            text-align: right;
            outline: none;
        }

        .dark-mode .return-refund-details {
            border-top-color: #334155;
        }

        .dark-mode .return-input-wrapper {
            background: #1e293b;
            border-color: #334155;
        }

        .dark-mode .return-input-prefix {
            background: #334155;
            border-right-color: #475569;
            color: #94a3b8;
        }

        .dark-mode .return-input {
            background: transparent;
            color: white;
        }

        /* Performance Section Styling */
        .analytics-container {
            padding: 30px 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .dark-mode .section-title {
            color: #f1f5f9;
        }

        .btn-download {
            background: #e0f2fe;
            color: #0369a1;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-download:hover {
            background: #bae6fd;
        }

        .performance-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #edf2f7;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 25px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .dark-mode .performance-card {
            background: #111827;
            border-color: #1f2937;
        }

        .metric-item {
            text-align: center;
            position: relative;
        }

        .metric-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 20%;
            height: 60%;
            width: 1px;
            background: #f1f5f9;
        }

        .dark-mode .metric-item:not(:last-child)::after {
            background: #1f2937;
        }

        .metric-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .metric-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b;
        }

        .dark-mode .metric-value {
            color: #f1f5f9;
        }

        .metric-icon {
            font-size: 0.9rem;
        }

        .text-sales {
            color: #0ea5e9;
        }

        .text-profit {
            color: #10b981;
        }

        .text-count {
            color: #a855f7;
        }

        .text-avg {
            color: #f97316;
        }

        .text-refund {
            color: #ef4444;
        }

        /* Inventory Valuation Styling */
        .valuation-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 30px;
        }

        .valuation-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #edf2f7;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .dark-mode .valuation-card {
            background: #111827;
            border-color: #1f2937;
        }

        .valuation-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .card-revenue::before {
            background: #10b981;
        }

        .card-cost::before {
            background: #6366f1;
        }

        .val-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .val-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .dark-mode .val-value {
            color: #f1f5f9;
        }

        .text-revenue {
            color: #10b981;
        }

        .text-cost-val {
            color: #1e293b;
        }

        .dark-mode .text-cost-val {
            color: #f1f5f9;
        }

        .val-desc {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* Sales Trends Styling */
        .trend-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #edf2f7;
            padding: 30px;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .dark-mode .trend-container {
            background: #111827;
            border-color: #1f2937;
        }

        .trend-header {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            overflow-x: auto;
            scrollbar-width: none;
            padding-bottom: 10px;
        }

        .trend-header::-webkit-scrollbar {
            display: none;
        }

        .trend-pills {
            background: #f1f5f9;
            padding: 6px;
            border-radius: 50px;
            display: flex;
            gap: 5px;
            white-space: nowrap;
        }

        .dark-mode .trend-pills {
            background: #1f2937;
        }

        .trend-pill {
            padding: 8px 30px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            background: transparent;
        }

        .trend-pill.active {
            background: white;
            color: #0369a1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .dark-mode .trend-pill.active {
            background: #374151;
            color: #7dd3fc;
        }

        .chart-wrapper {
            height: 250px;
            display: flex;
            align-items: flex-end;
            gap: 12px;
            padding: 0 10px;
            margin-bottom: 40px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .trend-column {
            flex: 1;
            min-width: 35px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .trend-bar-container {
            width: 100%;
            height: 0%;
            background: #e2e8f0;
            border-radius: 4px 4px 0 0;
            position: relative;
            transition: height 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .dark-mode .trend-bar-container {
            background: #1f2937;
        }

        .trend-bar-profit {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #10b981;
            transition: height 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.7;
        }

        .trend-column:hover .trend-bar-container {
            background: #cbd5e1;
        }

        .trend-column.active .trend-bar-container {
            background: #bae6fd;
            box-shadow: 0 0 0 2px #0ea5e9;
        }

        .dark-mode .trend-column.active .trend-bar-container {
            background: #0c4a6e;
        }

        .trend-date-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            margin-top: 10px;
        }

        .trend-column.active .trend-date-label {
            color: #0ea5e9;
        }

        /* Summary Bar Styling */
        .trend-summary-bar {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark-mode .trend-summary-bar {
            background: #1e293b;
        }

        .summary-period-info {
            display: flex;
            flex-direction: column;
        }

        .period-label {
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .period-date {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
        }

        .dark-mode .period-date {
            color: #f1f5f9;
        }

        .summary-metrics {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .summary-metric {
            text-align: right;
            min-width: 80px;
        }

        .sm-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: #64748b;
            margin-bottom: 2px;
        }

        .sm-value {
            font-size: 1.05rem;
            font-weight: 800;
        }

        .sm-sales {
            color: #0ea5e9;
        }

        .sm-profit {
            color: #10b981;
        }

        .sm-refund {
            color: #ef4444;
        }

        .sm-discount {
            color: #a855f7;
        }

        .sm-debt {
            color: #f97316;
        }

        .sm-cost {
            color: #1e293b;
        }

        .dark-mode .sm-cost {
            color: #cbd5e1;
        }

        /* Responsive Adjustments */
        @media (max-width: 1200px) {
            .performance-card {
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
            }

            .metric-item:nth-child(3)::after {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .analytics-container {
                padding: 20px 15px;
            }

            .valuation-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .val-value {
                font-size: 1.4rem;
            }

            .performance-card {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 20px 15px;
            }

            .metric-item::after {
                display: none !important;
            }

            .metric-value {
                font-size: 1.1rem;
            }

            .metric-label {
                font-size: 0.7rem;
                margin-bottom: 8px;
            }
        }

        @media (max-width: 480px) {
            .performance-card {
                grid-template-columns: 1fr;
                text-align: left;
            }

            .metric-label {
                justify-content: flex-start;
            }

            .metric-item {
                padding-bottom: 15px;
                border-bottom: 1px solid #f1f5f9;
            }

            .dark-mode .metric-item {
                border-bottom-color: #1f2937;
            }

            .metric-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }
        }
    </style>
</head>

<body class="pos-page">

    <nav class="top-nav">
        <a href="{{ route('admin.dashboard.page') }}" class="nav-brand">
            <span class="back-icon"><i class="bi bi-arrow-left"></i></span>
            ANALYTICS & REPORTS
        </a>

        <button class="menu-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse"
            data-bs-target="#topNavMenu" aria-expanded="false" aria-controls="topNavMenu">
            <i class="bi bi-list"></i>
        </button>

        <div class="collapse d-lg-flex top-nav-menu flex-grow-1 ms-lg-3" id="topNavMenu">
            <div class="nav-menu-inner d-flex w-100 gap-2">
                <div class="ms-lg-auto d-flex flex-wrap align-items-center gap-2 right-actions">
                    <button class="theme-toggle" id="posThemeToggle" type="button" aria-label="Toggle dark mode"
                        style="background:transparent;border:none;color:white;font-size:1.2rem;cursor:pointer;">
                        <i class="bi bi-moon-stars" id="posThemeToggleIcon"></i>
                    </button>
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

    <div class="analytics-container">
        <div class="section-header">
            <h2 class="section-title">This Month's Performance</h2>
        </div>

        <div class="performance-card">
            <div class="metric-item">
                <div class="metric-label">
                    <i class="bi bi-graph-up text-sales metric-icon"></i>
                    TOTAL SALES
                </div>
                <div class="metric-value">₱{{ number_format($totalSales, 2) }}</div>
            </div>

            <div class="metric-item">
                <div class="metric-label">
                    <i class="bi bi-wallet2 text-profit metric-icon"></i>
                    NET PROFIT
                </div>
                <div class="metric-value text-profit">₱{{ number_format($netProfit, 2) }}</div>
            </div>

            <div class="metric-item">
                <div class="metric-label">
                    <i class="bi bi-file-earmark-text text-count metric-icon"></i>
                    SALES COUNT
                </div>
                <div class="metric-value">{{ $salesCount }}</div>
            </div>

            <div class="metric-item">
                <div class="metric-label">
                    <i class="bi bi-pie-chart text-avg metric-icon"></i>
                    AVG. SALE
                </div>
                <div class="metric-value">₱{{ number_format($avgSale, 2) }}</div>
            </div>

            <div class="metric-item">
                <div class="metric-label">
                    <i class="bi bi-arrow-counterclockwise text-refund metric-icon"></i>
                    TOTAL REFUND
                </div>
                <div class="metric-value text-refund">₱{{ number_format($totalRefund, 2) }}</div>
            </div>
        </div>

        <div class="section-header" style="margin-top: 40px">
            <h2 class="section-title">Sales Trends</h2>
        </div>

        <div class="trend-container">
            <div class="trend-header">
                <div class="trend-pills">
                    <button class="trend-pill active" data-type="all">All</button>

                    @foreach ($admins as $admin)
                        <button class="trend-pill" data-type="admin_{{ $admin->id }}">
                            Admin: {{ $admin->fullname }}
                        </button>
                    @endforeach

                    @foreach ($cashiers as $cashier)
                        <button class="trend-pill" data-type="cashier_{{ $cashier->id }}">
                            Cashier: {{ $cashier->fullname }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="chart-wrapper" id="trendChart">
                <!-- Bars will be injected here by JS -->
            </div>

            <div class="trend-summary-bar">
                <div class="summary-period-info">
                    <div class="period-label">Selected Period</div>
                    <div class="period-date" id="selectedDate">--</div>
                </div>
                <div class="summary-metrics">
                    <div class="summary-metric">
                        <div class="sm-label">SALES</div>
                        <div class="sm-value sm-sales" id="smSales">₱0.00</div>
                    </div>
                    <div class="summary-metric">
                        <div class="sm-label">PROFIT</div>
                        <div class="sm-value sm-profit" id="smProfit">₱0.00</div>
                    </div>
                    <div class="summary-metric">
                        <div class="sm-label">PAID DEBT</div>
                        <div class="sm-value sm-debt" id="smDebt">₱0.00</div>
                    </div>
                    <div class="summary-metric">
                        <div class="sm-label">REFUNDS</div>
                        <div class="sm-value sm-refund" id="smRefund">₱0.00</div>
                    </div>
                    <div class="summary-metric">
                        <div class="sm-label">DISCOUNT</div>
                        <div class="sm-value sm-discount" id="smDiscount">₱0.00</div>
                    </div>
                    <div class="summary-metric">
                        <div class="sm-label">COST</div>
                        <div class="sm-value sm-cost" id="smCost">₱0.00</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-header" style="margin-top: 40px;">
            <h2 class="section-title">Inventory Valuation</h2>
        </div>

        <div class="valuation-grid">
            <div class="valuation-card card-revenue">
                <div class="val-label">Potential Revenue (Selling Price)</div>
                <div class="val-value text-revenue">₱{{ number_format($potentialRevenue, 2) }}</div>
                <div class="val-desc">Total value if all current stock is sold</div>
            </div>

            <div class="valuation-card card-cost">
                <div class="val-label">Total Inventory Cost</div>
                <div class="val-value text-cost-val">₱{{ number_format($totalInventoryCost, 2) }}</div>
                <div class="val-desc">Total cost invested in current stock</div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        // Sales Trends Logic
        const trendData = @json($trendData);
        let currentType = 'all';

        function formatCurrency(value) {
            return '₱' + new Intl.NumberFormat('en-PH', {
                minimumFractionDigits: 2
            }).format(value);
        }

        function updateChart(type) {
            const chart = document.getElementById('trendChart');
            const data = trendData[type];
            chart.innerHTML = '';

            // Find max sales for scaling
            let maxSales = 0;
            Object.values(data).forEach(d => {
                if (d.sales > maxSales) maxSales = d.sales;
            });
            if (maxSales === 0) maxSales = 100; // Default scale

            Object.keys(data).forEach(day => {
                const dayData = data[day];
                const height = (dayData.sales / maxSales) * 100;
                const profitHeight = dayData.sales > 0 ? (dayData.profit / dayData.sales) * 100 : 0;

                const column = document.createElement('div');
                column.className = 'trend-column';
                if (parseInt(day) === new Date().getDate()) column.classList.add('active');

                column.innerHTML = `
                    <div class="trend-bar-container" style="height: ${height}%">
                        <div class="trend-bar-profit" style="height: ${profitHeight}%"></div>
                    </div>
                    <div class="trend-date-label">${dayData.date}</div>
                `;

                column.addEventListener('click', () => {
                    document.querySelectorAll('.trend-column').forEach(c => c.classList.remove('active'));
                    column.classList.add('active');
                    showDaySummary(dayData);
                });

                chart.appendChild(column);

                if (column.classList.contains('active')) {
                    showDaySummary(dayData);
                }
            });
        }

        function showDaySummary(data) {
            document.getElementById('selectedDate').textContent = data.full_date;
            document.getElementById('smSales').textContent = formatCurrency(data.sales);
            document.getElementById('smProfit').textContent = formatCurrency(data.profit);
            document.getElementById('smRefund').textContent = formatCurrency(data.refunds);
            document.getElementById('smDiscount').textContent = formatCurrency(data.discount || 0);
            document.getElementById('smDebt').textContent = formatCurrency(0); // Static placeholder for now
            document.getElementById('smCost').textContent = formatCurrency(data.cost);
        }

        document.querySelectorAll('.trend-pill').forEach(pill => {
            pill.addEventListener('click', () => {
                document.querySelectorAll('.trend-pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                currentType = pill.dataset.type;
                updateChart(currentType);
            });
        });

        // Initialize chart
        updateChart('all');
    </script>
</body>

</html>
