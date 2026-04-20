<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;

class AdminPOSController extends Controller
{
    public function AdminPOSPage()
    {
        $products = Products::all();
        return view('admin.pos.index', compact('products'));
    }
}
