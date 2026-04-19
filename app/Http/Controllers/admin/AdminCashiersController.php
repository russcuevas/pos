<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCashiersController extends Controller
{
    public function AdminCashiersPage()
    {
        return view('admin.cashiers.index');
    }
}
