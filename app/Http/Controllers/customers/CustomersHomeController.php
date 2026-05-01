<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\CustomersCarts;
use Illuminate\Support\Facades\Auth;

class CustomersHomeController extends Controller
{
    public function CustomerHomePage()
    {
        $products = Products::where('is_show', 1)->get();
        $cartCount = 0;
        if (Auth::guard('web')->check()) {
            $cartCount = CustomersCarts::where('customers_id', Auth::guard('web')->id())->count();
        }
        return view('customers.home.index', compact('products', 'cartCount'));
    }
}
