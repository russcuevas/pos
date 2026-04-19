<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cashiers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminCashiersController extends Controller
{
    public function AdminCashiersPage()
    {
        $cashiers = Cashiers::all();
        return view('admin.cashiers.index', compact('cashiers'));
    }

    public function AdminCashiersCreate(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:cashiers,email',
            'password' => 'required|string|min:6',
        ]);

        Cashiers::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'inactive',
        ]);

        return redirect()->back()->with('success', 'Cashier added successfully.');
    }

    public function AdminCashiersUpdate(Request $request, $id)
    {
        $cashier = Cashiers::findOrFail($id);

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:cashiers,email,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $cashier->fullname = $request->fullname;
        $cashier->email = $request->email;
        $cashier->status = $request->status;

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $cashier->password = Hash::make($request->password);
        }

        $cashier->save();

        return redirect()->back()->with('success', 'Cashier updated successfully.');
    }

    public function AdminCashiersDelete($id)
    {
        $cashier = Cashiers::findOrFail($id);
        $cashier->delete();

        return redirect()->back()->with('success', 'Cashier deleted successfully.');
    }
}
