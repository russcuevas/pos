<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAnalyticsController extends Controller
{
    public function AdminAnalyticsPage()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // 1. Fetch completed orders with products and return items
        $ordersData = \App\Models\Orders::where('order_status', 'Completed')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->with(['product', 'returnItems'])
            ->get();

        $grossSales = 0;
        $totalCost = 0;
        $orderNumbersWithStock = [];

        foreach ($ordersData as $item) {
            $supplierPrice = $item->product ? $item->product->supplier_price : 0;
            
            // Calculate how much was actually KEPT (not returned)
            $returnedQty = $item->returnItems->sum('quantity');
            $activeQty = max(0, $item->quantity - $returnedQty);
            
            // If item was fully or partially returned, total_price needs to be adjusted for the "active" part
            // total_price in DB is for the original quantity.
            $unitPrice = $item->quantity > 0 ? ($item->total_price / $item->quantity) : 0;
            $activePrice = $activeQty * $unitPrice;

            $grossSales += $activePrice;
            $totalCost += ($activeQty * $supplierPrice);

            if ($activeQty > 0) {
                $orderNumbersWithStock[] = $item->order_number;
            }
        }

        // 2. Sum unique discounts for orders that still have active items
        $activeOrderNumbers = array_unique($orderNumbersWithStock);
        $totalDiscount = $ordersData->whereIn('order_number', $activeOrderNumbers)
            ->groupBy('order_number')
            ->map(function($items) {
                return $items->first()->discount_price ?? 0;
            })->sum();

        // 3. Final Metrics
        $totalSales = max(0, $grossSales - $totalDiscount);
        $netProfit = max(0, $totalSales - $totalCost);
        $salesCount = count($activeOrderNumbers);
        $avgSale = $salesCount > 0 ? ($totalSales / $salesCount) : 0;

        // 4. Total Refund (Actual money returned this month)
        $totalRefund = \App\Models\ReturnItems::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('refund_amount');

        // 5. Inventory Valuation (Global)
        $products = \App\Models\Products::all();
        $potentialRevenue = 0;
        $totalInventoryCost = 0;

        foreach ($products as $product) {
            $qty = $product->quantity ?? 0;
            $potentialRevenue += ($qty * ($product->selling_price ?? 0));
            $totalInventoryCost += ($qty * ($product->supplier_price ?? 0));
        }

        // 6. Sales Trends Data (Current Month)
        $daysInMonth = now()->daysInMonth;
        
        $admins = \App\Models\Admins::all();
        $cashiers = \App\Models\Cashiers::all();

        $trendData = [
            'all' => []
        ];

        // Initialize keys for each user
        foreach ($admins as $admin) { $trendData['admin_' . $admin->id] = []; }
        foreach ($cashiers as $cashier) { $trendData['cashier_' . $cashier->id] = []; }

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = now()->setDay($i)->format('Y-m-d');
            $start = $date . ' 00:00:00';
            $end = $date . ' 23:59:59';

            foreach (array_keys($trendData) as $type) {
                $query = \App\Models\Orders::whereBetween('created_at', [$start, $end])
                    ->where('order_status', 'completed')
                    ->with(['product', 'returnItems']);

                if (str_starts_with($type, 'admin_')) {
                    $id = str_replace('admin_', '', $type);
                    $query->where('admin_id', $id);
                } elseif (str_starts_with($type, 'cashier_')) {
                    $id = str_replace('cashier_', '', $type);
                    $query->where('cashier_id', $id);
                }

                $orders = $query->get();
                $daySales = 0;
                $dayProfit = 0;
                $dayCost = 0;

                foreach ($orders as $order) {
                    $returnedQty = $order->returnItems->sum('quantity');
                    $activeQty = max(0, $order->quantity - $returnedQty);
                    
                    if ($activeQty > 0) {
                        $sellingPrice = $order->total_price / $order->quantity;
                        $itemCost = $order->product->supplier_price ?? 0;
                        
                        $daySales += $sellingPrice * $activeQty;
                        $dayProfit += ($sellingPrice - $itemCost) * $activeQty;
                        $dayCost += $itemCost * $activeQty;
                    }
                }

                // Discounts for this day (based on unique orders)
                $dayDiscount = $orders->groupBy('order_number')
                    ->map(function($items) {
                        return $items->first()->discount_price ?? 0;
                    })->sum();

                // Refunds for this day
                $refundQuery = \App\Models\ReturnItems::whereBetween('created_at', [$start, $end]);
                if (str_starts_with($type, 'admin_')) {
                    $id = str_replace('admin_', '', $type);
                    $refundQuery->whereHas('orderItem', function($q) use ($id) { $q->where('admin_id', $id); });
                } elseif (str_starts_with($type, 'cashier_')) {
                    $id = str_replace('cashier_', '', $type);
                    $refundQuery->whereHas('orderItem', function($q) use ($id) { $q->where('cashier_id', $id); });
                }
                $dayRefund = $refundQuery->sum('refund_amount');

                $trendData[$type][$i] = [
                    'date' => now()->setDay($i)->format('M d'),
                    'full_date' => now()->setDay($i)->format('l, M d'),
                    'sales' => $daySales,
                    'profit' => $dayProfit,
                    'refunds' => $dayRefund,
                    'discount' => $dayDiscount,
                    'cost' => $dayCost
                ];
            }
        }

        return view('admin.analytics.index', compact(
            'totalSales', 
            'netProfit', 
            'salesCount', 
            'avgSale', 
            'totalRefund',
            'potentialRevenue',
            'totalInventoryCost',
            'trendData',
            'admins',
            'cashiers'
        ));
    }
}
