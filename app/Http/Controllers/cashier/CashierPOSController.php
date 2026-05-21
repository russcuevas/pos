<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Products;
use App\Models\CashiersCarts;
use App\Models\Orders;
use App\Models\SaveOrders;
use App\Models\PettyCash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CashierPOSController extends Controller
{
    public function CashierPOSPage()
    {
        $products = Products::all();
        $cashier_id = Auth::guard('cashier')->id();

        $cartItems = CashiersCarts::where('cashier_id', $cashier_id)
            ->leftJoin('products', 'cashiers_carts.product_id', '=', 'products.id')
            ->select('cashiers_carts.*', 'products.product_name', 'products.selling_price', 'products.supplier_price', 'products.product_image', 'products.whole_sale_qty', 'products.whole_sale_price')
            ->get();

        $subtotal = 0;
        $total_cost = 0;
        foreach ($cartItems as $item) {
            $line_total = 0;
            $wholesale_bundles = 0;
            $regular_items = $item->quantity;

            if ($item->product_id) {
                if (!empty($item->whole_sale_qty) && $item->whole_sale_qty > 0 && $item->quantity >= $item->whole_sale_qty) {
                    $wholesale_bundles = floor($item->quantity / $item->whole_sale_qty);
                    $regular_items = fmod($item->quantity, $item->whole_sale_qty);
                    $line_total = ($wholesale_bundles * $item->whole_sale_price) + ($regular_items * $item->selling_price);
                } else {
                    $line_total = $item->quantity * $item->selling_price;
                }
                $total_cost += ($item->quantity * $item->supplier_price);
            } else {
                $line_total = $item->quantity * $item->custom_price;
                $item->product_name = $item->custom_entry;
                $item->selling_price = $item->custom_price;
            }

            $item->line_total = $line_total;
            $item->wholesale_bundles = $wholesale_bundles;
            $item->regular_items = $regular_items;

            $subtotal += $line_total;
        }

        $profit = $total_cost;

        $savedOrdersData = SaveOrders::where('cashier_id', $cashier_id)
            ->leftJoin('products', 'save_orders.product_id', '=', 'products.id')
            ->select('save_orders.*', 'products.product_name', 'products.product_image', 'products.selling_price', 'products.whole_sale_qty', 'products.whole_sale_price')
            ->orderBy('save_orders.created_at', 'desc')
            ->get();

        $savedOrders = $savedOrdersData->groupBy('reference_save_order');

        $pending_count = Orders::whereNotIn('order_status', ['Completed', 'Cancelled'])
            ->distinct('order_number')
            ->count('order_number');

        $pettyCash = PettyCash::where('cashier_id', $cashier_id)
            ->whereDate('opening_time', Carbon::today())
            ->first();

        $salesToday = 0;
        $walkInSales = 0;
        $onlineSales = 0;
        if ($pettyCash) {
            $baseQuery = Orders::where('cashier_id', $cashier_id)
                ->where('order_status', 'Completed')
                ->whereDate('created_at', Carbon::today());

            $salesToday = $baseQuery->sum('total_price');
            $walkInSales = (clone $baseQuery)->where('order_type', 'Walk In')->sum('total_price');
            $onlineSales = (clone $baseQuery)->where('order_type', '!=', 'Walk In')->sum('total_price');
        }

        return view('cashier.pos.index', compact('products', 'cartItems', 'subtotal', 'profit', 'savedOrders', 'pending_count', 'pettyCash', 'salesToday', 'walkInSales', 'onlineSales'));
    }

    public function StartShift(Request $request)
    {
        $request->validate([
            'beginning_balance' => 'required|numeric|min:0'
        ]);

        $cashier_id = Auth::guard('cashier')->id();

        PettyCash::create([
            'cashier_id' => $cashier_id,
            'beginning_balance' => $request->beginning_balance,
            'opening_time' => Carbon::now(),
        ]);

        return back()->with('success', 'Shift started successfully!');
    }

    public function EditPettyCash(Request $request)
    {
        $request->validate([
            'beginning_balance' => 'required|numeric|min:0'
        ]);

        $cashier_id = Auth::guard('cashier')->id();

        $pettyCash = PettyCash::where('cashier_id', $cashier_id)
            ->whereDate('opening_time', Carbon::today())
            ->first();

        if ($pettyCash) {
            $pettyCash->update([
                'beginning_balance' => $request->beginning_balance
            ]);
            return back()->with('success', 'Petty Cash updated successfully!');
        }

        return back()->with('error', 'Petty Cash record not found for today.');
    }

    public function CashierAddToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01'
        ]);

        $cashier_id = Auth::guard('cashier')->id();

        $cartItem = CashiersCarts::where('product_id', $request->product_id)
            ->where('cashier_id', $cashier_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CashiersCarts::create([
                'admin_id' => null,
                'cashier_id' => $cashier_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Added to cart successfully');
    }

    public function CashierScanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = trim($request->barcode);
        $product = Products::where('product_code', $barcode)->first();

        if (!$product) {
            return back()->with('error', 'No product found');
        }

        $cashier_id = Auth::guard('cashier')->id();

        $cartItem = CashiersCarts::where('product_id', $product->id)
            ->where('cashier_id', $cashier_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            CashiersCarts::create([
                'admin_id' => null,
                'cashier_id' => $cashier_id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return back()->with('success', 'Added: ' . $product->product_name);
    }

    public function CashierUpdateCart(Request $request, $id)
    {
        $cartItem = CashiersCarts::findOrFail($id);

        if ($request->has('action')) {
            $request->validate(['action' => 'required|in:increment,decrement']);
            if ($request->action === 'increment') {
                $cartItem->quantity += 1;
            } elseif ($request->action === 'decrement') {
                if ($cartItem->quantity > 1) {
                    $cartItem->quantity -= 1;
                } else {
                    $cartItem->delete();
                    return redirect()->back();
                }
            }
        } elseif ($request->has('quantity')) {
            $request->validate(['quantity' => 'required|numeric|min:0.01']);
            $cartItem->quantity = $request->quantity;
        }

        $cartItem->save();
        return redirect()->back()->with('success', 'Item quantity updated successfully');
    }

    public function CashierDeleteCart($id)
    {
        $cartItem = CashiersCarts::findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart!');
    }

    public function CashierCheckout(Request $request)
    {
        $cashier_id = Auth::guard('cashier')->id();
        $cartItems = CashiersCarts::where('cashier_id', $cashier_id)
            ->leftJoin('products', 'cashiers_carts.product_id', '=', 'products.id')
            ->select('cashiers_carts.*', 'products.selling_price', 'products.whole_sale_qty', 'products.whole_sale_price')
            ->get();

        if ($cartItems->isEmpty()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty!'], 422);
            }
            return back()->with('error', 'Cart is empty!');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'discount_price' => 'nullable|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        $order_total_price = $request->total_price;
        $payment_amount = $request->payment_amount;
        $discount_price = $request->discount_price ?? 0;

        if ($payment_amount < $order_total_price) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Payment amount is less than total due!'], 422);
            }
            return back()->with('error', 'Payment amount is less than total due!');
        }

        $change_amount = $payment_amount - $order_total_price;

        $maxOrderNum = Orders::selectRaw('MAX(CAST(REPLACE(order_number, "#OR", "") AS UNSIGNED)) as max_num')->value('max_num');
        $orId = $maxOrderNum ? $maxOrderNum + 1 : 1;
        $order_number = '#OR' . $orId;

        foreach ($cartItems as $item) {
            $item_line_total = 0;
            if ($item->product_id) {
                if (!empty($item->whole_sale_qty) && $item->whole_sale_qty > 0 && $item->quantity >= $item->whole_sale_qty) {
                    $wholesale_bundles = floor($item->quantity / $item->whole_sale_qty);
                    $regular_items = fmod($item->quantity, $item->whole_sale_qty);
                    $item_line_total = ($wholesale_bundles * $item->whole_sale_price) + ($regular_items * $item->selling_price);
                } else {
                    $item_line_total = $item->quantity * $item->selling_price;
                }
            } else {
                $item_line_total = $item->quantity * $item->custom_price;
            }

            Orders::create([
                'order_number' => $order_number,
                'products_id' => $item->product_id,
                'custom_entry' => $item->custom_entry,
                'custom_price' => $item->custom_price,
                'customer_id' => null,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'address' => $request->address,
                'quantity' => $item->quantity,
                'discount_price' => $discount_price,
                'total_price' => $item_line_total,
                'payment_amount' => $payment_amount,
                'change_amount' => $change_amount,
                'payment_method' => $request->payment_method,
                'order_type' => 'Walk In',
                'order_status' => 'completed',
                'delivery_fee' => null,
                'remarks' => null,
                'cashier_id' => $cashier_id,
                'admin_id' => null,
            ]);

            if ($item->product_id) {
                $product = \App\Models\Products::find($item->product_id);
                if ($product) {
                    $product->quantity = max(0, $product->quantity - $item->quantity);
                    $product->save();
                }
            }
        }

        CashiersCarts::where('cashier_id', $cashier_id)->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'order_number' => $order_number, 'message' => 'Checkout Successful! OR Number: ' . $order_number]);
        }

        return back()->with('success', 'Checkout Successful! OR Number: ' . $order_number);
    }

    public function CashierAddCustomCart(Request $request)
    {
        $cashier_id = Auth::guard('cashier')->id();

        $request->validate([
            'custom_entry' => 'required|string|max:255',
            'custom_price' => 'required|numeric|min:0',
        ]);

        CashiersCarts::create([
            'admin_id' => null,
            'cashier_id' => $cashier_id,
            'product_id' => null,
            'custom_entry' => $request->custom_entry,
            'custom_price' => $request->custom_price,
            'quantity' => 1
        ]);

        return back()->with('success', 'Custom item added!');
    }

    public function CashierSaveOrder()
    {
        $cashier_id = Auth::guard('cashier')->id();
        $cartItems = CashiersCarts::where('cashier_id', $cashier_id)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Cart is empty!');
        }

        $reference = 'SO-' . strtoupper(uniqid());

        foreach ($cartItems as $item) {
            SaveOrders::create([
                'reference_save_order' => $reference,
                'cashier_id' => $cashier_id,
                'admin_id' => null,
                'product_id' => $item->product_id,
                'custom_entry' => $item->custom_entry,
                'custom_price' => $item->custom_price,
                'quantity' => $item->quantity,
            ]);
        }

        CashiersCarts::where('cashier_id', $cashier_id)->delete();

        return back()->with('success', 'Order saved successfully!');
    }

    public function CashierLoadSavedOrder($reference)
    {
        $cashier_id = Auth::guard('cashier')->id();
        $savedItems = SaveOrders::where('reference_save_order', $reference)
            ->where('cashier_id', $cashier_id)
            ->get();

        if ($savedItems->isEmpty()) {
            return back()->with('error', 'Saved order not found!');
        }

        foreach ($savedItems as $item) {
            if ($item->product_id) {
                $cartItem = CashiersCarts::where('cashier_id', $cashier_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($cartItem) {
                    $cartItem->quantity += $item->quantity;
                    $cartItem->save();
                } else {
                    CashiersCarts::create([
                        'admin_id' => null,
                        'cashier_id' => $cashier_id,
                        'product_id' => $item->product_id,
                        'custom_entry' => null,
                        'custom_price' => null,
                        'quantity' => $item->quantity,
                    ]);
                }
            } else {
                CashiersCarts::create([
                    'admin_id' => null,
                    'cashier_id' => $cashier_id,
                    'product_id' => null,
                    'custom_entry' => $item->custom_entry,
                    'custom_price' => $item->custom_price,
                    'quantity' => $item->quantity,
                ]);
            }
        }

        SaveOrders::where('reference_save_order', $reference)
            ->where('cashier_id', $cashier_id)
            ->delete();

        return back()->with('success', 'Saved order loaded to cart!');
    }

    public function CashierDeleteSavedOrder($reference)
    {
        $cashier_id = Auth::guard('cashier')->id();
        SaveOrders::where('reference_save_order', $reference)
            ->where('cashier_id', $cashier_id)
            ->delete();

        return back()->with('success', 'Saved order deleted!');
    }
}
