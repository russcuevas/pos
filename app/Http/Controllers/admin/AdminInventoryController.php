<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminInventoryController extends Controller
{
    public function AdminInventoryPage()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $products = Products::leftJoinSub(
            Orders::select('products_id', DB::raw('SUM(quantity) as sold_this_month'))
                ->where('order_status', 'completed')
                ->whereMonth('updated_at', $currentMonth)
                ->whereYear('updated_at', $currentYear)
                ->groupBy('products_id'),
            'sales',
            'products.id',
            '=',
            'sales.products_id'
        )
        ->select('products.*', DB::raw('IFNULL(sales.sold_this_month, 0) as sold_this_month'))
        ->get();

        $totalProducts = $products->count();
        $safeCount = $products->where('quantity', '>', 50)->count();
        $lowCount = $products->whereBetween('quantity', [11, 50])->count();
        $criticalCount = $products->where('quantity', '<=', 10)->count();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'safeCount', 'lowCount', 'criticalCount'));
    }
}
