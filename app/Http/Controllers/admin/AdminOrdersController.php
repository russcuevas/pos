<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Products;
use App\Models\OrdersChats;
use App\Models\ReturnItems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminOrdersController extends Controller
{
    public function AdminOrdersPage()
    {
        $orders = Orders::whereIn('order_status', ['Completed', 'Cancelled'])
            ->with(['product', 'returnItems'])
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

        // Fetch and group returns by created_at timestamp (to group items returned together)
        $returnItems = ReturnItems::where('order_number', $orderNumber)
            ->with('orderItem.product')
            ->orderBy('created_at', 'desc')
            ->get();

        $returns = $returnItems->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d H:i:s');
        })->map(function ($batchItems) {
            return (object) [
                'created_at' => $batchItems->first()->created_at,
                'refund_amount' => $batchItems->sum('refund_amount'),
                'refund_source' => $batchItems->first()->refund_source,
                'items' => $batchItems
            ];
        })->values();

        $isCancelled = strtolower($first->order_status) === 'cancelled';

        $originalTotal = 0;
        $originalProfit = 0;
        $activeTotal = 0;
        $activeProfit = 0;

        foreach ($items as $item) {
            $supplierPrice = $item->product ? $item->product->supplier_price : 0;

            // Calculate already returned quantity for this item
            $item->returned_quantity = $item->returnItems->sum('quantity');
            $item->remaining_quantity = $item->quantity - $item->returned_quantity;

            $item_original_profit = $isCancelled ? 0 : ($item->quantity * $supplierPrice);
            $item->original_profit = $item_original_profit;

            $item_active_profit = $isCancelled ? 0 : ($item->remaining_quantity * $supplierPrice);
            $item->active_profit = $item_active_profit;

            $item_unit_price = $item->quantity > 0 ? ($item->total_price / $item->quantity) : 0;
            $item->active_price = $item->remaining_quantity * $item_unit_price;

            $originalTotal += $item->total_price;
            $originalProfit += $item_original_profit;
            $activeTotal += $item->active_price;
            $activeProfit += $item_active_profit;
        }

        // Apply discount to the final active total
        $discount = $first->discount_price ?? 0;
        $finalActiveTotal = max(0, $activeTotal - $discount);
        $finalOriginalTotal = max(0, $originalTotal - $discount);

        // Profit calculation remains consistent with user's logic (Supplier Price sum - Discount)
        $finalActiveProfit = $isCancelled ? 0 : max(0, $activeProfit - $discount);
        $finalOriginalProfit = $isCancelled ? 0 : max(0, $originalProfit - $discount);

        return (object) [
            'order_number' => $orderNumber,
            'status' => $first->order_status,
            'created_at' => $first->created_at,
            'updated_at' => $first->updated_at,
            'customer_name' => $first->customer_name,
            'customer_phone' => $first->customer_phone,
            'items' => $items,
            'total_amount' => $finalActiveTotal,
            'original_total' => $finalOriginalTotal,
            'total_profit' => $finalActiveProfit,
            'original_total_profit' => $finalOriginalProfit,
            'chats_count' => $chats->count(),
            'chats' => $chats,
            'note' => $chats->first()?->message ?? null,
            'discount_price' => $first->discount_price,
            'payment_method' => $first->payment_method,
            'payment_amount' => $first->payment_amount,
            'change_amount' => $first->change_amount,
            'returns' => $returns,
            'total_refunded' => $returnItems->sum('refund_amount'),
        ];
    }

    public function ProcessReturn(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
            'refund_source' => 'required',
            'refund_amount' => 'required|numeric',
            'items' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $adminId = Auth::guard('admin')->id();

            foreach ($request->items as $itemData) {
                $orderItem = Orders::findOrFail($itemData['id']);

                ReturnItems::create([
                    'order_number' => $request->order_number,
                    'order_item_id' => $orderItem->id,
                    'quantity' => $itemData['quantity'],
                    'refund_amount' => $itemData['refund_amount'],
                    'refund_source' => $request->refund_source,
                    'admin_id' => $adminId,
                ]);

                // Update product stock only if not a specific fractional quantity (0.25, 0.33, 0.5, 0.75)
                if ($orderItem->products_id) {
                    $product = Products::find($orderItem->products_id);
                    if ($product) {
                        $qty = (float)$itemData['quantity'];
                        $roundedQty = round($qty, 2);
                        $fractionalQtys = [0.25, 0.33, 0.5, 0.75];

                        if (!in_array($roundedQty, $fractionalQtys)) {
                            $product->increment('quantity', $qty);

                            // Add stock history
                            \App\Models\ProductStockHistory::create([
                                'product_id' => $product->id,
                                'quantity' => $qty,
                                'cost_price' => $product->supplier_price,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Return processed successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function PrintReceipt($order_number)
    {
        $items = Orders::where('order_number', $order_number)
            ->with(['product', 'returnItems'])
            ->get();

        if ($items->isEmpty()) {
            abort(404, 'Order not found');
        }

        $order = $this->formatOrder($items, $order_number);

        $lineWidth = 32;
        $output = "";

        // HEADER
        $output .= str_pad("SAMMER'S STORE", $lineWidth, " ", STR_PAD_BOTH) . "\n";
        $output .= str_pad("Order Details", $lineWidth, " ", STR_PAD_BOTH) . "\n";
        $output .= str_pad("Receipt " . $order->order_number, $lineWidth, " ", STR_PAD_BOTH) . "\n";
        $output .= str_pad($order->updated_at->format('Y-m-d H:i'), $lineWidth, " ", STR_PAD_BOTH) . "\n";
        $output .= str_pad("Customer: " . ($order->customer_name ?: 'New Customer'), $lineWidth, " ", STR_PAD_BOTH) . "\n";
        $output .= str_repeat("-", $lineWidth) . "\n";

        // ITEMS
        foreach ($order->items as $item) {
            $isReturned = $item->returned_quantity >= $item->quantity;

            $qty = $item->quantity + 0;
            $name = $item->product ? $item->product->product_name : 'Product';
            $priceText = "P" . number_format($item->total_price, 2);

            if ($isReturned) {
                $name = "[RETURNED] " . $name;
            }

            $leftText = $qty . "x " . $name;
            $nameWidth = $lineWidth - strlen($priceText);

            $wrappedName = wordwrap($leftText, $nameWidth, "\n", true);
            $nameLines = explode("\n", $wrappedName);

            foreach ($nameLines as $index => $line) {
                if ($index === 0) {
                    $output .= str_pad($line, $nameWidth);
                    $output .= $priceText . "\n";
                } else {
                    $output .= $line . "\n";
                }
            }
        }

        $output .= str_repeat("-", $lineWidth) . "\n";

        // SUBTOTAL
        $subtotal = "P" . number_format($order->original_total + ($order->discount_price ?? 0), 2);
        $output .= str_pad("Subtotal", $lineWidth - strlen($subtotal));
        $output .= $subtotal . "\n";

        // DISCOUNT
        if (($order->discount_price ?? 0) > 0) {
            $discount = "-P" . number_format($order->discount_price, 2);
            $output .= str_pad("Discount", $lineWidth - strlen($discount));
            $output .= $discount . "\n";
        }

        // REFUNDS
        if (($order->total_refunded ?? 0) > 0) {
            $output .= str_repeat("-", $lineWidth) . "\n";

            $originalTotal = "P" . number_format($order->original_total, 2);
            $output .= str_pad("Original Total", $lineWidth - strlen($originalTotal));
            $output .= $originalTotal . "\n";

            $refunds = "-P" . number_format($order->total_refunded, 2);
            $output .= str_pad("Refunds", $lineWidth - strlen($refunds));
            $output .= $refunds . "\n";
        }

        $output .= str_repeat("-", $lineWidth) . "\n";

        // TOTAL
        $totalLabel = ($order->total_refunded ?? 0) > 0 ? "NET TOTAL" : "TOTAL";
        $totalText = "P" . number_format($order->total_amount, 2);

        $output .= str_pad($totalLabel, $lineWidth - strlen($totalText));
        $output .= $totalText . "\n";

        $output .= str_repeat("-", $lineWidth) . "\n";

        // PAYMENT
        $cash = "P" . number_format($order->payment_amount ?? 0, 2);
        $output .= str_pad("Cash", $lineWidth - strlen($cash));
        $output .= $cash . "\n";

        $change = "P" . number_format($order->change_amount ?? 0, 2);
        $output .= str_pad("Change Given", $lineWidth - strlen($change));
        $output .= $change . "\n";

        $output .= str_repeat("-", $lineWidth) . "\n";
        $output .= str_pad("Thank you for your order!", $lineWidth, " ", STR_PAD_BOTH) . "\n\n\n";

        return response($output)
            ->header('Content-Type', 'text/plain');
    }
}
