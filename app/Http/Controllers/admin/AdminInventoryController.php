<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function AdminInventoryPage()
    {
        return view('admin.inventory.index');
    }
}
