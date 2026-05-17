<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Orders;
use App\Models\Products;
use App\Models\DebtorsTransactions;
use App\Models\DebtorsItems;

class AdminDashboardController extends Controller
{
    public function AdminDashboardPage()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Stats for Today
        $todayStats = $this->getStatsForDate($today);
        // Stats for Yesterday
        $yesterdayStats = $this->getStatsForDate($yesterday);

        // Debt Paid Today & This Month
        $debtPaidToday = DebtorsTransactions::whereDate('created_at', $today)->sum('payment_amount');
        $debtPaidThisMonth = DebtorsTransactions::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('payment_amount');

        // Total Products
        $totalProducts = Products::count();
        $productsAddedThisMonth = Products::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // Calculate trends
        $salesTrend = $todayStats['sales'] - $yesterdayStats['sales'];
        $profitTrend = $todayStats['profit'] - $yesterdayStats['profit'];
        
        $debtPaidTrendPercent = $debtPaidThisMonth > 0 ? round(($debtPaidToday / $debtPaidThisMonth) * 100) : ($debtPaidToday > 0 ? 100 : 0);
        $productsAddedTrendPercent = $totalProducts > 0 ? round(($productsAddedThisMonth / $totalProducts) * 100) : 0;

        return view('admin.dashboard.index', compact(
            'todayStats', 
            'salesTrend', 
            'profitTrend', 
            'debtPaidToday', 
            'debtPaidTrendPercent', 
            'totalProducts', 
            'productsAddedTrendPercent'
        ));
    }

    private function getStatsForDate($date)
    {
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        $ordersData = Orders::where('order_status', 'Completed')
            ->whereBetween('updated_at', [$startOfDay, $endOfDay])
            ->with(['product', 'returnItems'])
            ->get();

        $debtItems = DebtorsItems::whereBetween('debtors_items.created_at', [$startOfDay, $endOfDay])
            ->leftJoin('products', 'debtors_items.products_id', '=', 'products.id')
            ->select('debtors_items.*', 'products.selling_price as prod_selling', 'products.supplier_price as prod_supplier')
            ->get();

        $debtTransactions = DebtorsTransactions::whereBetween('created_at', [$startOfDay, $endOfDay])->get();

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

        $totalSales = max(0, $cashGrossSales - $totalDiscount) + $debtPaymentsReceived;
        $netProfit = max(0, ($cashGrossSales - $cashTotalCost) - $totalDiscount) + $debtProfit;

        return [
            'sales' => $totalSales,
            'profit' => $netProfit,
        ];
    }
}
