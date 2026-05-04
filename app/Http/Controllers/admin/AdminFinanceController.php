<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FinanceRecords;

class AdminFinanceController extends Controller
{
    public function AdminFinancePage(Request $request)
    {
        $method = $request->get('method', 'ALL');
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $query = FinanceRecords::orderBy('created_at', 'desc');

        if ($method !== 'ALL') {
            $query->where('payment_method', $method);
        }

        if ($month !== 'ALL') {
            $query->whereMonth('created_at', $month);
        }

        if ($year !== 'ALL') {
            $query->whereYear('created_at', $year);
        }

        $records = $query->get();

        $totalInflow = $records->where('type', 'income')->sum('amount');
        $totalOutflow = $records->where('type', 'expense')->sum('amount');
        $availableBalance = $totalInflow - $totalOutflow;

        $categoryBreakdown = $records->groupBy('category')->map(function ($row) {
            return $row->sum('amount');
        });

        return view('admin.finance.index', compact('records', 'totalInflow', 'totalOutflow', 'availableBalance', 'categoryBreakdown', 'method', 'month', 'year'));
    }

    public function AdminFinanceAdd(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'payment_method' => 'required|in:E-cash,CASH',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'note' => 'nullable|string',
        ]);

        FinanceRecords::create([
            'type' => $request->type,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'category' => $request->category,
            'note' => $request->note,
        ]);

        $message = $request->type == 'income' ? 'Income entry saved successfully!' : 'Expense entry saved successfully!';
        return redirect()->back()->with('success', $message);
    }
}
