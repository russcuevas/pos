<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPendingOrdersController extends Controller
{
    public function AdminPendingOrdersPage()
    {
        return view('admin.pending_orders.index');
    }
}
