<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Products;
use App\Models\OrdersChats;

class AdminOrdersController extends Controller
{
    public function AdminOrdersPage()
    {
        // Fetch orders that are 'Completed' or 'Cancelled'
        $orders = Orders::whereIn('order_status', ['Completed', 'Cancelled'])
            ->with('product')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('order_number');

        $groupedOrders = $orders->map(function ($items, $orderNumber) {
            return $this->formatOrder($items, $orderNumber);
        });

        $pending_count = Orders::whereNotIn('order_status', ['Completed', 'Cancelled'])
            ->distinct('order_number')
            ->count('order_number');

        return view('admin.orders.index', [
            'orders' => $groupedOrders,
            'pending_count' => $pending_count
        ]);
    }

    private function formatOrder($items, $orderNumber)
    {
        $first = $items->first();
        $orderIds = $items->pluck('id')->toArray();
        $chats = OrdersChats::whereIn('order_id', $orderIds)
            ->orderBy('created_at', 'asc')
            ->get();

        $isCancelled = strtolower($first->order_status) === 'cancelled';
        $totalProfit = 0;
        foreach ($items as $item) {
            $supplierPrice = $item->product ? $item->product->supplier_price : 0;
            $itemProfit = $isCancelled ? 0 : ($item->quantity * $supplierPrice);
            $item->profit = $itemProfit; // Attach profit to item
            $totalProfit += $itemProfit;
        }

        return (object) [
            'order_number' => $orderNumber,
            'status' => $first->order_status,
            'created_at' => $first->created_at,
            'updated_at' => $first->updated_at,
            'customer_name' => $first->customer_name,
            'customer_phone' => $first->customer_phone,
            'items' => $items,
            'total_amount' => $items->sum('total_price') - ($first->discount_price ?? 0),
            'original_total' => $items->sum('total_price'),
            'total_profit' => $isCancelled ? 0 : ($totalProfit - ($first->discount_price ?? 0)),
            'chats_count' => $chats->count(),
            'chats' => $chats,
            'note' => $chats->first()?->message ?? null,
            'discount_price' => $first->discount_price,
            'payment_method' => $first->payment_method,
            'payment_amount' => $first->payment_amount,
            'change_amount' => $first->change_amount,
        ];
    }
}
