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

        return view('admin.analytics.index', compact(
            'totalSales', 
            'netProfit', 
            'salesCount', 
            'avgSale', 
            'totalRefund',
            'potentialRevenue',
            'totalInventoryCost'
        ));
    }
}
