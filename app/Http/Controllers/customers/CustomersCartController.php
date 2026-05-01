<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CustomersCarts;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;

class CustomersCartController extends Controller
{
    public function CustomersAddToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $customerId = Auth::guard('web')->id();

        if (!$customerId) {
            return response()->json(['success' => false, 'message' => 'Please login to add items to cart.']);
        }

        $product = Products::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }

        if ($product->quantity < $quantity) {
            return response()->json(['success' => false, 'message' => 'Insufficient stock.']);
        }

        $cartItem = CustomersCarts::where('customers_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CustomersCarts::create([
                'customers_id' => $customerId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Product added to cart.']);
    }

    public function CustomersGetCart()
    {
        $customerId = Auth::guard('web')->id();
        $cartItems = CustomersCarts::where('customers_id', $customerId)
            ->with('product')
            ->get();

        $processedCart = $cartItems->map(function ($item) {
            $product = $item->product;
            $line_total = 0;
            $wholesale_bundles = 0;
            $regular_items = $item->quantity;

            if ($product->whole_sale_qty > 0 && $item->quantity >= $product->whole_sale_qty) {
                $wholesale_bundles = floor($item->quantity / $product->whole_sale_qty);
                $regular_items = fmod($item->quantity, $product->whole_sale_qty);
                $line_total = ($wholesale_bundles * $product->whole_sale_price) + ($regular_items * $product->selling_price);
            } else {
                $line_total = $item->quantity * $product->selling_price;
            }

            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'product' => $product,
                'line_total' => $line_total,
                'wholesale_bundles' => $wholesale_bundles,
                'regular_items' => $regular_items,
            ];
        });

        return response()->json([
            'success' => true,
            'cart' => $processedCart
        ]);
    }

    public function CustomersUpdateCart(Request $request)
    {
        $cartId = $request->input('cart_id');
        $action = $request->input('action'); // 'increment' or 'decrement'
        $customerId = Auth::guard('web')->id();

        $cartItem = CustomersCarts::where('customers_id', $customerId)
            ->where('id', $cartId)
            ->with('product')
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.']);
        }

        if ($action === 'increment') {
            if ($cartItem->product->quantity <= $cartItem->quantity) {
                return response()->json(['success' => false, 'message' => 'Not enough stock.']);
            }
            $cartItem->quantity += 1;
        } elseif ($action === 'decrement') {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity -= 1;
            } else {
                $cartItem->delete();
                return response()->json(['success' => true, 'message' => 'Item removed from cart.']);
            }
        }

        $cartItem->save();
        return response()->json(['success' => true, 'message' => 'Cart updated.']);
    }

    public function CustomersDeleteCart(Request $request)
    {
        $cartId = $request->input('cart_id');
        $customerId = Auth::guard('web')->id();

        $cartItem = CustomersCarts::where('customers_id', $customerId)
            ->where('id', $cartId)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true, 'message' => 'Item removed from cart.']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found.']);
    }
}
