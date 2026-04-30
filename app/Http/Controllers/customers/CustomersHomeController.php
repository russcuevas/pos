<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\Products;

class CustomersHomeController extends Controller
{
    public function CustomerHomePage()
    {
        $products = Products::where('is_show', 1)->get();
        return view('customers.home.index', compact('products'));
    }
}
