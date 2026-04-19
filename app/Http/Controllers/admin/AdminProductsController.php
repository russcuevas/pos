<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Products;

class AdminProductsController extends Controller
{
    public function AdminProductsPage()
    {
        $products = Products::all();
        return view('admin.products.index', compact('products'));
    }

    public function AdminProductsCreate(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:255|unique:products,product_code',
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'selling_price' => 'required|numeric|min:0',
            'supplier_price' => 'required|numeric|min:0',
            'whole_sale_qty' => 'required|numeric|min:0',
            'whole_sale_price' => 'required|numeric|min:0',
        ]);

        $imageName = null;
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = $image->hashName(); // Generates a unique hash name for the file
            $image->move(public_path('images/products'), $imageName);
        }

        Products::create([
            'product_code' => $request->product_code,
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'product_image' => $imageName,
            'selling_price' => $request->selling_price,
            'supplier_price' => $request->supplier_price,
            'whole_sale_qty' => $request->whole_sale_qty,
            'whole_sale_price' => $request->whole_sale_price,
            'is_show' => $request->has('is_show') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    public function AdminProductsUpdate(Request $request, $id)
    {
        $request->validate([
            'product_code' => 'required|string|max:255|unique:products,product_code,' . $id,
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'selling_price' => 'required|numeric|min:0',
            'supplier_price' => 'required|numeric|min:0',
            'whole_sale_qty' => 'required|numeric|min:0',
            'whole_sale_price' => 'required|numeric|min:0',
        ]);

        $product = Products::findOrFail($id);

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = $image->hashName();
            $image->move(public_path('images/products'), $imageName);
            $product->product_image = $imageName;
        }

        $product->product_code = $request->product_code;
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->selling_price = $request->selling_price;
        $product->supplier_price = $request->supplier_price;
        $product->whole_sale_qty = $request->whole_sale_qty;
        $product->whole_sale_price = $request->whole_sale_price;
        $product->is_show = $request->has('is_show') ? 1 : 0;
        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function AdminProductsDelete($id)
    {
        $product = Products::findOrFail($id);

        // Optional: delete the image file from public directory as well
        if ($product->product_image && file_exists(public_path('images/products/' . $product->product_image))) {
            unlink(public_path('images/products/' . $product->product_image));
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
}
