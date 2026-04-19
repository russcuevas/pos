<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminProductsController extends Controller
{
    public function AdminProductsPage()
    {
        return view('admin.products.index');
    }
}
