<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DebtorsAccount;
use App\Models\DebtorsTransactions;
use App\Models\DebtorsItems;

class AdminAnalyticsController extends Controller
{
    public function AdminAnalyticsPage()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $daysInMonth = now()->daysInMonth;

        // --- 1. Fetch Core Data (Optimized) ---
        // Fetch all completed orders for the month once
        $ordersData = \App\Models\Orders::where('order_status', 'Completed')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->with(['product', 'returnItems'])
            ->get();

        // Fetch all debt items for the month
        $debtItems = DebtorsItems::whereBetween('debtors_items.created_at', [$startOfMonth, $endOfMonth])
            ->leftJoin('products', 'debtors_items.products_id', '=', 'products.id')
            ->select('debtors_items.*', 'products.selling_price as prod_selling', 'products.supplier_price as prod_supplier')
            ->get();

        // Fetch all debt transactions (payments) for the month
        $debtTransactions = DebtorsTransactions::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        // Fetch all refunds for the month
        $refunds = \App\Models\ReturnItems::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        // --- 2. Calculate Monthly Totals (Accrual Basis) ---
        $cashGrossSales = 0;
        $cashTotalCost = 0;
        $activeOrderNumbers = [];

        foreach ($ordersData as $orderItem) {
            $supplierPrice = $orderItem->product ? $orderItem->product->supplier_price : 0;
            $returnedQty = $orderItem->returnItems->sum('quantity');
            $activeQty = max(0, $orderItem->quantity - $returnedQty);

            if ($activeQty > 0) {
                $unitPrice = $orderItem->quantity > 0 ? ($orderItem->total_price / $orderItem->quantity) : 0;
                $cashGrossSales += ($activeQty * $unitPrice);
                $cashTotalCost += ($activeQty * $supplierPrice);
                $activeOrderNumbers[] = $orderItem->order_number;
            }
        }

        $activeOrderNumbers = array_unique($activeOrderNumbers);
        $totalDiscount = $ordersData->whereIn('order_number', $activeOrderNumbers)
            ->groupBy('order_number')
            ->map(function ($items) {
                return $items->first()->discount_price ?? 0;
            })->sum();

        $netDebtAdded = 0;
        $debtProfit = 0;
        $debtCost = 0;

        foreach ($debtItems as $dItem) {
            $sellingPrice = $dItem->products_id ? $dItem->prod_selling : $dItem->custom_price;
            $supplierPrice = $dItem->products_id ? $dItem->prod_supplier : ($dItem->custom_cost ?? 0);

            $val = $sellingPrice * $dItem->quantity;
            $cost = $supplierPrice * $dItem->quantity;

            $netDebtAdded += $val;
            $debtCost += $cost;
            $debtProfit += ($val - $cost);
        }

        $debtPaymentsReceived = $debtTransactions->sum('payment_amount');

        // Final Aggregate Metrics
        // Total Sales is now cash-based: Completed Cash Orders + Debt Payments Received
        $totalSales = max(0, $cashGrossSales - $totalDiscount) + $debtPaymentsReceived;
        
        // Net Profit remains accrual-based: (Cash Profit) + (All Debt Profit)
        $netProfit = max(0, ($cashGrossSales - $cashTotalCost) - $totalDiscount) + $debtProfit;
        
        // Sales Count now includes Cash Orders + Debt Payment Transactions
        $salesCount = count($activeOrderNumbers) + $debtTransactions->count();
        $avgSale = $salesCount > 0 ? ($totalSales / $salesCount) : 0;
        
        $debtBatchCountMonth = $debtItems->unique('batch_number')->count();
        $avgDebtSale = $debtBatchCountMonth > 0 ? ($netDebtAdded / $debtBatchCountMonth) : 0;

        $totalRefund = $refunds->sum('refund_amount');

        // --- 3. Inventory & Valuation ---
        $allProducts = \App\Models\Products::all();
        $stockValue = 0;
        $stockCost = 0;
        foreach ($allProducts as $product) {
            $stockValue += (($product->quantity ?? 0) * ($product->selling_price ?? 0));
            $stockCost += (($product->quantity ?? 0) * ($product->supplier_price ?? 0));
        }

        $totalOutstandingDebt = DebtorsAccount::sum('balance');
        $potentialRevenue = $stockValue + $totalOutstandingDebt;

        // Estimate Cost of Outstanding Debt
        $unpaidDebtCost = 0;
        if ($netDebtAdded > 0) {
            $costRatio = $debtCost / $netDebtAdded;
            $unpaidDebtCost = $totalOutstandingDebt * $costRatio;
        }
        $totalInventoryCost = $stockCost + $unpaidDebtCost;

        // Realized Metrics (Based on the "Cash-based" Sales logic)
        $realizedRevenue = $totalSales;
        
        // Realized Cost = Cash Order Cost + (Estimated Cost of the Debt that was actually paid)
        $paidDebtCost = 0;
        if ($netDebtAdded > 0) {
            $paidDebtCost = $debtPaymentsReceived * ($debtCost / $netDebtAdded);
        }
        $realizedCost = $cashTotalCost + $paidDebtCost;

        // --- 4. Trend Data Preparation (Optimized) ---
        $admins = \App\Models\Admins::all();
        $cashiers = \App\Models\Cashiers::all();
        $trendData = ['all' => []];

        foreach ($admins as $admin) $trendData['admin_' . $admin->id] = [];
        foreach ($cashiers as $cashier) $trendData['cashier_' . $cashier->id] = [];

        // Pre-group data by day for efficiency
        $ordersByDay = $ordersData->groupBy(fn($q) => $q->updated_at->format('Y-m-d'));
        $debtItemsByDay = $debtItems->groupBy(fn($q) => \Carbon\Carbon::parse($q->created_at)->format('Y-m-d'));
        $debtTransByDay = $debtTransactions->groupBy(fn($q) => \Carbon\Carbon::parse($q->created_at)->format('Y-m-d'));
        $refundsByDay = $refunds->groupBy(fn($q) => \Carbon\Carbon::parse($q->created_at)->format('Y-m-d'));

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateStr = now()->setDay($i)->format('Y-m-d');
            $displayDate = now()->setDay($i)->format('M d');
            $fullDate = now()->setDay($i)->format('l, M d');

            foreach (array_keys($trendData) as $type) {
                $daySales = 0; $dayCost = 0; $dayDiscount = 0;
                $dayRefund = 0; $dayPaidDebt = 0; $dayDebtAdded = 0; $dayDebtCost = 0;

                // 4a. Orders & Refunds (Attributed to Users)
                $dayOrders = $ordersByDay->get($dateStr, collect());
                $dayRefunds = $refundsByDay->get($dateStr, collect());

                $userId = null;
                $userType = null;
                if (str_starts_with($type, 'admin_')) {
                    $userId = str_replace('admin_', '', $type);
                    $userType = 'admin_id';
                } elseif (str_starts_with($type, 'cashier_')) {
                    $userId = str_replace('cashier_', '', $type);
                    $userType = 'cashier_id';
                }

                // Filter by user if not 'all'
                $filteredOrders = ($type === 'all') ? $dayOrders : $dayOrders->where($userType, $userId);
                $filteredRefunds = ($type === 'all') ? $dayRefunds : $dayRefunds->where($userType, $userId);

                $orderNumbersInDay = [];
                foreach ($filteredOrders as $orderItem) {
                    $returnedQty = $orderItem->returnItems->sum('quantity');
                    $activeQty = max(0, $orderItem->quantity - $returnedQty);
                    if ($activeQty > 0) {
                        $unitPrice = $orderItem->quantity > 0 ? ($orderItem->total_price / $orderItem->quantity) : 0;
                        $daySales += ($activeQty * $unitPrice);
                        $dayCost += ($activeQty * ($orderItem->product->supplier_price ?? 0));
                        $orderNumbersInDay[] = $orderItem->order_number;
                    }
                }

                $dayDiscount = $filteredOrders->whereIn('order_number', array_unique($orderNumbersInDay))
                    ->groupBy('order_number')
                    ->map(fn($items) => $items->first()->discount_price ?? 0)
                    ->sum();

                $dayRefund = $filteredRefunds->sum('refund_amount');

                // 4b. Debt (Currently not attributed to specific users in DB)
                if ($type === 'all') {
                    $dayDebtItems = $debtItemsByDay->get($dateStr, collect());
                    foreach ($dayDebtItems as $dItem) {
                        $selling = $dItem->products_id ? $dItem->prod_selling : $dItem->custom_price;
                        $supplier = $dItem->products_id ? $dItem->prod_supplier : ($dItem->custom_cost ?? 0);
                        $dayDebtAdded += ($selling * $dItem->quantity);
                        $dayDebtCost += ($supplier * $dItem->quantity);
                    }
                    $dayPaidDebt = $debtTransByDay->get($dateStr, collect())->sum('payment_amount');
                }

                $finalSales = max(0, $daySales - $dayDiscount) + $dayPaidDebt;
                $finalProfit = max(0, ($daySales - $dayCost) - $dayDiscount) + ($dayDebtAdded - $dayDebtCost);

                $trendData[$type][$i] = [
                    'date' => $displayDate,
                    'full_date' => $fullDate,
                    'sales' => $finalSales,
                    'profit' => $finalProfit,
                    'refunds' => $dayRefund,
                    'discount' => $dayDiscount,
                    'cost' => $dayCost + $dayDebtCost,
                    'paid_debt' => $dayPaidDebt
                ];
            }
        }

        return view('admin.analytics.index', compact(
            'totalSales', 'netProfit', 'salesCount', 'avgSale', 'totalRefund',
            'potentialRevenue', 'totalInventoryCost', 'realizedRevenue', 'realizedCost',
            'trendData', 'admins', 'cashiers', 'totalOutstandingDebt',
            'netDebtAdded', 'debtPaymentsReceived', 'debtProfit', 'avgDebtSale'
        ));
    }
}
