<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Orders;
use App\Models\ProductStockHistory;
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
        ->with('stockHistory')
        ->get();

        $totalProducts = $products->count();
        $safeCount = $products->where('quantity', '>', 50)->count();
        $lowCount = $products->whereBetween('quantity', [11, 50])->count();
        $criticalCount = $products->where('quantity', '<=', 10)->count();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'safeCount', 'lowCount', 'criticalCount'));
    }

    public function AdminAddStock(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
        ]);

        ProductStockHistory::create([
            'product_id' => $productId,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
        ]);

        $product = Products::find($productId);
        $product->quantity += $request->quantity;
        $product->supplier_price = $request->cost_price;
        $product->save();

        return redirect()->back()->with('success', 'Stock added successfully.');
    }

    public function AdminUpdateStock(Request $request, $historyId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $history = ProductStockHistory::findOrFail($historyId);
        $diff = $request->quantity - $history->quantity;

        $history->update([
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
        ]);

        $product = Products::find($history->product_id);
        $product->increment('quantity', $diff);

        return redirect()->back()->with('success', 'Stock batch updated successfully.');
    }

    public function AdminDeleteStock($historyId)
    {
        $history = ProductStockHistory::findOrFail($historyId);
        $product = Products::find($history->product_id);
        $product->decrement('quantity', $history->quantity);
        $history->delete();

        return redirect()->back()->with('success', 'Stock batch deleted successfully.');
    }
}
