<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Orders;
use App\Models\OrdersChats;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomersOrderController extends Controller
{
    public function CustomersOrdersPage()
    {
        $customerId = Auth::guard('web')->id();

        // Fetch orders grouped by order_number
        $orders = Orders::where('customer_id', $customerId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('order_number');

        $groupedOrders = $orders->map(function ($items, $orderNumber) {
            $first = $items->first();
            $orderIds = $items->pluck('id')->toArray();
            $chats = OrdersChats::whereIn('order_id', $orderIds)
                ->orderBy('created_at', 'asc')
                ->get();

            $status = strtolower($first->order_status);
            // If completed, the admin saves the final total in total_price of each row.
            // If not, it's the sum of line totals.
            $totalAmount = ($status === 'completed') ? $first->total_price : $items->sum('total_price');

            return (object) [
                'order_number' => $orderNumber,
                'status' => $first->order_status,
                'created_at' => $first->created_at,
                'items' => $items,
                'total_amount' => $totalAmount,
                'discount' => $first->discount_price ?? 0,
                'chats_count' => $chats->count(),
                'chats' => $chats,
                'note' => $chats->first()?->message ?? null,
            ];
        });

        return view('customers.orders.index', [
            'orders' => $groupedOrders,
            'products' => \App\Models\Products::where('is_show', 1)->where('quantity', '>', 0)->get()
        ]);
    }

    public function AddOrderItem(Request $request)
    {
        $orderNumber = $request->input('order_number');
        $productId = $request->input('product_id');
        $customerId = Auth::guard('web')->id();

        $product = \App\Models\Products::find($productId);
        if (!$product || $product->quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Product not found or out of stock.']);
        }

        // Check if order exists and is pending
        $existingOrder = Orders::where('order_number', $orderNumber)
            ->where('customer_id', $customerId)
            ->first();

        if (!$existingOrder || strtolower($existingOrder->order_status) !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Order not found or cannot be modified.']);
        }

        // Check if product already in this order
        $orderItem = Orders::where('order_number', $orderNumber)
            ->where('products_id', $productId)
            ->first();

        $isExistingItem = (bool)$orderItem;

        if ($orderItem) {
            $orderItem->quantity += 1;
        } else {
            $orderItem = new Orders();
            $orderItem->customer_id = $customerId;
            $orderItem->order_number = $orderNumber;
            $orderItem->products_id = $productId;
            $orderItem->quantity = 1;
            $orderItem->order_status = $existingOrder->order_status;

            // Copy metadata from existing order
            $orderItem->customer_name = $existingOrder->customer_name;
            $orderItem->customer_phone = $existingOrder->customer_phone;
            $orderItem->address = $existingOrder->address;
            $orderItem->payment_method = $existingOrder->payment_method;
            $orderItem->order_type = $existingOrder->order_type;
            $orderItem->delivery_fee = $existingOrder->delivery_fee;
        }

        // Recalculate price
        $wholesale_bundles = 0;
        $regular_items = 0;
        if ($product->whole_sale_qty > 0 && $orderItem->quantity >= $product->whole_sale_qty) {
            $wholesale_bundles = (int)floor($orderItem->quantity / $product->whole_sale_qty);
            $regular_items = (int)($orderItem->quantity % $product->whole_sale_qty);
            $line_total = ($wholesale_bundles * $product->whole_sale_price) + ($regular_items * $product->selling_price);
        } else {
            $line_total = $orderItem->quantity * $product->selling_price;
        }

        $orderItem->total_price = $line_total;
        $orderItem->save();

        // Get new totals for the order
        $newOrderTotal = Orders::where('order_number', $orderNumber)->sum('total_price');

        // Prepare wholesale data for UI
        $isWholesale = $wholesale_bundles > 0;

        return response()->json([
            'success' => true,
            'message' => 'Product added to order.',
            'is_new' => !isset($isExistingItem) || !$isExistingItem, // We need to track if it was existing
            'item_id' => $orderItem->id,
            'product_name' => $product->product_name,
            'new_qty' => (int)$orderItem->quantity,
            'new_line_total' => number_format($orderItem->total_price, 2),
            'new_order_total' => number_format($newOrderTotal, 2),
            'is_wholesale' => $isWholesale,
            'wholesale_bundles' => $wholesale_bundles,
            'regular_items' => $regular_items
        ]);
    }

    public function UpdateOrderQuantity(Request $request)
    {
        $orderId = $request->input('order_id');
        $action = $request->input('action');
        $customerId = Auth::guard('web')->id();

        $orderItem = Orders::where('id', $orderId)
            ->where('customer_id', $customerId)
            ->with('product')
            ->first();

        if (!$orderItem) {
            return response()->json(['success' => false, 'message' => 'Order item not found.']);
        }

        if (strtolower($orderItem->order_status) !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending orders can be modified.']);
        }

        if ($action === 'increment') {
            if ($orderItem->product->quantity <= 0) {
                return response()->json(['success' => false, 'message' => 'Product is out of stock.']);
            }
            $orderItem->quantity += 1;
        } elseif ($action === 'decrement') {
            if ($orderItem->quantity > 1) {
                $orderItem->quantity -= 1;
            } else {
                // If quantity becomes 0, we might want to delete the item
                // and if it's the last item in the order, maybe handle that?
                // For simplicity, just delete the row.
                $orderItem->delete();
                return response()->json(['success' => true, 'message' => 'Item removed from order.']);
            }
        }

        // Recalculate price
        $product = $orderItem->product;
        $wholesale_bundles = 0;
        $regular_items = 0;
        if ($product->whole_sale_qty > 0 && $orderItem->quantity >= $product->whole_sale_qty) {
            $wholesale_bundles = (int)floor($orderItem->quantity / $product->whole_sale_qty);
            $regular_items = (int)($orderItem->quantity % $product->whole_sale_qty);
            $line_total = ($wholesale_bundles * $product->whole_sale_price) + ($regular_items * $product->selling_price);
        } else {
            $line_total = $orderItem->quantity * $product->selling_price;
        }

        $orderItem->total_price = $line_total;
        $orderItem->save();

        // Get new order total
        $newOrderTotal = Orders::where('order_number', $orderItem->order_number)->sum('total_price');

        return response()->json([
            'success' => true,
            'message' => 'Order updated.',
            'new_qty' => (int)$orderItem->quantity,
            'new_line_total' => number_format($orderItem->total_price, 2),
            'new_order_total' => number_format($newOrderTotal, 2),
            'is_wholesale' => $wholesale_bundles > 0,
            'wholesale_bundles' => $wholesale_bundles,
            'regular_items' => $regular_items
        ]);
    }

    public function SendChat(Request $request)
    {
        $orderNumber = $request->input('order_number');
        $message = $request->input('message');
        $customerId = Auth::guard('web')->id();

        if (empty($message)) {
            return response()->json(['success' => false, 'message' => 'Message cannot be empty.']);
        }

        $order = Orders::where('order_number', $orderNumber)
            ->where('customer_id', $customerId)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        if (in_array(strtolower($order->order_status), ['completed', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'Chat is disabled for completed/cancelled orders.']);
        }

        $chat = OrdersChats::create([
            'order_id' => $order->id,
            'customer_id' => $customerId,
            'message' => $message,
            'allowed' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent.',
            'chat' => [
                'message' => $chat->message,
                'time' => $chat->created_at->format('g:i:s A'),
                'is_customer' => true
            ]
        ]);
    }

    public function GetMessages($orderNumber)
    {
        $customerId = Auth::guard('web')->id();
        $orderIds = Orders::where('order_number', $orderNumber)
            ->where('customer_id', $customerId)
            ->pluck('id')
            ->toArray();

        $chats = OrdersChats::whereIn('order_id', $orderIds)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'message' => $chat->message,
                    'time' => $chat->created_at->format('g:i:s A'),
                    // logic: customer_id is null and allowed is not null means it's an admin message
                    'is_customer' => !($chat->customer_id === null && $chat->allowed !== null)
                ];
            });

        return response()->json(['success' => true, 'chats' => $chats]);
    }

    public function GetOrdersStatus()
    {
        $customerId = Auth::guard('web')->id();

        // Get statuses
        $orders = Orders::where('customer_id', $customerId)
            ->select('order_number', 'order_status')
            ->get()
            ->groupBy('order_number');

        $statuses = $orders->map(function ($items) {
            return $items->first()->order_status;
        });

        // Get chat counts for these orders
        $orderNumbers = $orders->keys();
        $chatCounts = OrdersChats::join('orders', 'orders_chats.order_id', '=', 'orders.id')
            ->whereIn('orders.order_number', $orderNumbers)
            ->where('orders.customer_id', $customerId)
            ->select('orders.order_number', DB::raw('count(*) as total'))
            ->groupBy('orders.order_number')
            ->pluck('total', 'orders.order_number');

        return response()->json([
            'success' => true,
            'statuses' => $statuses,
            'chat_counts' => $chatCounts
        ]);
    }
}
