<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPendingOrdersController extends Controller
{
    public function AdminPendingOrdersPage()
    {
        // Fetch orders grouped by order_number that are not 'Completed' or 'Cancelled'
        $orders = \App\Models\Orders::whereNotIn('order_status', ['Completed', 'Cancelled'])
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('order_number');

        $groupedOrders = $orders->map(function ($items, $orderNumber) {
            $first = $items->first();
            $orderIds = $items->pluck('id')->toArray();
            $chats = \App\Models\OrdersChats::whereIn('order_id', $orderIds)
                ->orderBy('created_at', 'asc')
                ->get();

            return (object) [
                'order_number' => $orderNumber,
                'status' => $first->order_status,
                'created_at' => $first->created_at,
                'customer_name' => $first->customer_name,
                'customer_phone' => $first->customer_phone,
                'items' => $items,
                'total_amount' => $items->sum('total_price'),
                'chats_count' => $chats->count(),
                'chats' => $chats,
                'note' => $chats->first()?->message ?? null,
            ];
        });

        return view('admin.pending_orders.index', [
            'orders' => $groupedOrders,
            'products' => \App\Models\Products::where('is_show', 1)->where('quantity', '>', 0)->get()
        ]);
    }

    public function AddOrderItem(Request $request)
    {
        $orderNumber = $request->input('order_number');
        $productId = $request->input('product_id');

        $product = \App\Models\Products::find($productId);
        if (!$product || $product->quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Product not found or out of stock.']);
        }

        $existingOrder = \App\Models\Orders::where('order_number', $orderNumber)->first();

        if (!$existingOrder || in_array(strtolower($existingOrder->order_status), ['completed', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'Order not found or cannot be modified.']);
        }

        $orderItem = \App\Models\Orders::where('order_number', $orderNumber)
            ->where('products_id', $productId)
            ->first();

        $isExistingItem = (bool)$orderItem;

        if ($orderItem) {
            $orderItem->quantity += 1;
        } else {
            $orderItem = new \App\Models\Orders();
            $orderItem->customer_id = $existingOrder->customer_id;
            $orderItem->order_number = $orderNumber;
            $orderItem->products_id = $productId;
            $orderItem->quantity = 1;
            $orderItem->order_status = $existingOrder->order_status;
            $orderItem->customer_name = $existingOrder->customer_name;
            $orderItem->customer_phone = $existingOrder->customer_phone;
            $orderItem->address = $existingOrder->address;
            $orderItem->payment_method = $existingOrder->payment_method;
            $orderItem->order_type = $existingOrder->order_type;
            $orderItem->delivery_fee = $existingOrder->delivery_fee;
        }

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

        $newOrderTotal = \App\Models\Orders::where('order_number', $orderNumber)->sum('total_price');

        return response()->json([
            'success' => true,
            'message' => 'Product added to order.',
            'is_new' => !$isExistingItem,
            'item_id' => $orderItem->id,
            'product_name' => $product->product_name,
            'new_qty' => (int)$orderItem->quantity,
            'new_line_total' => number_format($orderItem->total_price, 2),
            'new_order_total' => number_format($newOrderTotal, 2),
            'is_wholesale' => $wholesale_bundles > 0,
            'wholesale_bundles' => $wholesale_bundles,
            'regular_items' => $regular_items
        ]);
    }

    public function UpdateOrderQuantity(Request $request)
    {
        $orderId = $request->input('order_id');
        $action = $request->input('action');

        $orderItem = \App\Models\Orders::where('id', $orderId)
            ->with('product')
            ->first();

        if (!$orderItem) {
            return response()->json(['success' => false, 'message' => 'Order item not found.']);
        }

        if (in_array(strtolower($orderItem->order_status), ['completed', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'This order cannot be modified.']);
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
                $orderItem->delete();
                $newTotal = \App\Models\Orders::where('order_number', $orderItem->order_number)->sum('total_price');
                return response()->json(['success' => true, 'message' => 'Item removed from order.', 'removed' => true, 'new_order_total' => number_format($newTotal, 2)]);
            }
        }

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

        $newOrderTotal = \App\Models\Orders::where('order_number', $orderItem->order_number)->sum('total_price');

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

        if (empty($message)) {
            return response()->json(['success' => false, 'message' => 'Message cannot be empty.']);
        }

        $order = \App\Models\Orders::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        $chat = \App\Models\OrdersChats::create([
            'order_id' => $order->id,
            'customer_id' => null, // Admin message
            'message' => $message,
            'allowed' => \Illuminate\Support\Facades\Auth::guard('admin')->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent.',
            'chat' => [
                'message' => $chat->message,
                'time' => $chat->created_at->format('g:i:s A'),
                'is_customer' => false
            ]
        ]);
    }

    public function GetMessages($orderNumber)
    {
        $orderIds = \App\Models\Orders::where('order_number', $orderNumber)
            ->pluck('id')
            ->toArray();

        $chats = \App\Models\OrdersChats::whereIn('order_id', $orderIds)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'message' => $chat->message,
                    'time' => $chat->created_at->format('g:i:s A'),
                    'is_customer' => !($chat->customer_id === null && $chat->allowed !== null)
                ];
            });

        return response()->json(['success' => true, 'chats' => $chats]);
    }

    public function CancelOrder(Request $request)
    {
        $orderNumber = $request->input('order_number');
        \App\Models\Orders::where('order_number', $orderNumber)
            ->update(['order_status' => 'Cancelled']);
        
        return response()->json(['success' => true, 'message' => 'Order cancelled successfully.']);
    }

    public function StartPreparing(Request $request)
    {
        $orderNumber = $request->input('order_number');
        \App\Models\Orders::where('order_number', $orderNumber)
            ->update(['order_status' => 'Preparing']);
        
        return response()->json(['success' => true, 'message' => 'Order preparation started.']);
    }

    public function MarkAsReady(Request $request)
    {
        $orderNumber = $request->input('order_number');
        \App\Models\Orders::where('order_number', $orderNumber)
            ->update(['order_status' => 'Ready']);
        
        return response()->json(['success' => true, 'message' => 'Order marked as ready.']);
    }

    public function CheckoutOrder(Request $request)
    {
        try {
            $orderNumber = $request->input('order_number');
            $discount = (float)$request->input('discount_price', 0);
            $paymentAmount = (float)$request->input('payment_amount');
            $paymentMethod = $request->input('payment_method');
            $totalPrice = (float)$request->input('total_price');

            $orderItems = \App\Models\Orders::where('order_number', $orderNumber)->get();

            if ($orderItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Order not found.']);
            }

            if ($paymentAmount < $totalPrice) {
                return response()->json(['success' => false, 'message' => 'Payment amount is less than total due.']);
            }

            $changeAmount = $paymentAmount - $totalPrice;

            foreach ($orderItems as $item) {
                // Update item to completed status
                $item->order_status = 'Completed';
                $item->payment_method = $paymentMethod;
                $item->payment_amount = $paymentAmount;
                $item->change_amount = $changeAmount;
                $item->discount_price = $discount;
                $item->total_price = $totalPrice;
                $item->save();

                // Deduct stock if it's a product
                if ($item->products_id) {
                    $product = \App\Models\Products::find($item->products_id);
                    if ($product) {
                        $product->quantity = max(0, $product->quantity - $item->quantity);
                        $product->save();
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Order completed successfully. Status updated to Completed.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
