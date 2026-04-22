<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\CashiersCarts;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;

class AdminPOSController extends Controller
{
    public function AdminPOSPage()
    {
        $products = Products::all();
        $admin_id = Auth::guard('admin')->id();

        $cartItems = CashiersCarts::where('admin_id', $admin_id)
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

        $profit = $subtotal - $total_cost;

        return view('admin.pos.index', compact('products', 'cartItems', 'subtotal', 'profit'));
    }

    public function AdminAddToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01'
        ]);

        $admin_id = Auth::guard('admin')->id();

        $cartItem = CashiersCarts::where('product_id', $request->product_id)
            ->where('admin_id', $admin_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CashiersCarts::create([
                'admin_id' => $admin_id,
                'cashier_id' => null,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Added to cart successfully');
    }

    public function AdminUpdateCart(Request $request, $id)
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

    public function AdminDeleteCart($id)
    {
        $cartItem = CashiersCarts::findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart!');
    }

    public function AdminCheckout(Request $request)
    {
        $admin_id = Auth::guard('admin')->id();
        $cartItems = CashiersCarts::where('admin_id', $admin_id)->get();

        if ($cartItems->isEmpty()) {
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

        $total_price = $request->total_price;
        $payment_amount = $request->payment_amount;
        $discount_price = $request->discount_price ?? 0;

        if ($payment_amount < $total_price) {
            return back()->with('error', 'Payment amount is less than total due!');
        }

        $change_amount = $payment_amount - $total_price;

        $latestOrder = Orders::latest('id')->first();
        $orId = $latestOrder ? $latestOrder->id + 1 : 1;
        $order_number = '#OR' . $orId;

        foreach ($cartItems as $item) {
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
                'total_price' => $total_price,
                'payment_amount' => $payment_amount,
                'change_amount' => $change_amount,
                'payment_method' => $request->payment_method,
                'order_type' => 'Walk In',
                'order_status' => 'completed',
                'delivery_fee' => null,
                'remarks' => null,
                'cashier_id' => null,
                'admin_id' => $admin_id,
            ]);

            if ($item->product_id) {
                $product = Products::find($item->product_id);
                if ($product) {
                    $product->quantity = max(0, $product->quantity - $item->quantity);
                    $product->save();
                }
            }
        }

        CashiersCarts::where('admin_id', $admin_id)->delete();

        return back()->with('success', 'Checkout Successful! OR Number: ' . $order_number);
    }

    public function AdminAddCustomCart(Request $request)
    {
        $admin_id = Auth::guard('admin')->id();

        $request->validate([
            'custom_entry' => 'required|string|max:255',
            'custom_price' => 'required|numeric|min:0',
        ]);

        CashiersCarts::create([
            'admin_id' => $admin_id,
            'cashier_id' => null,
            'product_id' => null,
            'custom_entry' => $request->custom_entry,
            'custom_price' => $request->custom_price,
            'quantity' => 1
        ]);

        return back()->with('success', 'Custom item added!');
    }
}
