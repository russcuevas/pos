<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DebtorsAccount;
use App\Models\DebtorsTransactions;
use App\Models\DebtorsItems;
use App\Models\Products;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminDebtorsController extends Controller
{
    public function AdminDebtorsPage()
    {
        $debtors = DebtorsAccount::withCount(['transactions', 'items'])->orderBy('created_at', 'desc')->get();
        return view('admin.debtors.index', compact('debtors'));
    }

    public function AdminDebtorsCreate(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'balance' => 'nullable|numeric|min:0',
        ]);

        DebtorsAccount::create([
            'customer_name' => $request->customer_name,
            'customer_contact' => $request->customer_contact,
            'customer_address' => $request->customer_address,
            'balance' => $request->balance ?? 0,
        ]);

        return redirect()->back()->with('success', 'Debtor account created successfully!');
    }

    public function AdminDebtorsView($id)
    {
        $debtor = DebtorsAccount::findOrFail($id);
        $products = Products::where('is_show', true)->get();

        $transactions = DebtorsTransactions::where('debtor_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Items from products
        $productItems = DebtorsItems::where('debtor_id', $id)
            ->whereNotNull('products_id')
            ->join('products', 'debtors_items.products_id', '=', 'products.id')
            ->select('debtors_items.*', 'products.selling_price', 'products.supplier_price', 'products.product_name as title')
            ->get();

        // Custom items
        $customItems = DebtorsItems::where('debtor_id', $id)
            ->whereNull('products_id')
            ->select('debtors_items.*', 'debtors_items.custom_price as selling_price', DB::raw('COALESCE(debtors_items.custom_cost, debtors_items.custom_price) as supplier_price'), 'debtors_items.custom_entry as title')
            ->get();

        $items = $productItems->concat($customItems);

        $totalCredit = $items->sum(fn($i) => $i->selling_price * $i->quantity);
        $totalPaid = $transactions->sum('payment_amount');
        $totalProfit = $items->sum(fn($i) => ($i->selling_price - $i->supplier_price) * $i->quantity);

        $activity = collect();

        foreach ($transactions as $t) {
            $activity->push([
                'type' => 'payment',
                'title' => 'Payment (' . $t->payment_type . ')',
                'amount' => $t->payment_amount,
                'created_at' => $t->created_at,
                'icon' => 'bi-cash-stack',
                'color' => 'success',
                'badge_color' => 'green',
                'note' => $t->note
            ]);
        }

        // Group by batch_number for items
        $groupedItems = $items->groupBy(fn($i) => $i->batch_number ?? $i->created_at->toDateTimeString());
        foreach ($groupedItems as $batchId => $batch) {
            $batchAmount = $batch->sum(fn($i) => $i->selling_price * $i->quantity);
            $batchProfit = $batch->sum(fn($i) => ($i->selling_price - $i->supplier_price) * $i->quantity);

            $activity->push([
                'type' => 'debt',
                'title' => 'Added Debt',
                'amount' => $batchAmount,
                'profit' => $batchProfit,
                'created_at' => $batch->first()->created_at,
                'icon' => 'bi-arrow-up-short',
                'color' => 'danger',
                'badge_color' => 'red',
                'items_count' => $batch->count(),
                'batch_items' => $batch,
                'batch_number' => $batch->first()->batch_number
            ]);
        }

        $activity = $activity->sortByDesc('created_at');

        return view('admin.debtors.view', compact('debtor', 'activity', 'totalCredit', 'totalPaid', 'totalProfit', 'products'));
    }

    public function AdminDebtorsAddPayment(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|string|in:Cash,E-Cash',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($id, $request) {
            DebtorsTransactions::create([
                'debtor_id' => $id,
                'payment_amount' => $request->payment_amount,
                'payment_type' => $request->payment_type,
                'note' => $request->note,
            ]);

            $debtor = DebtorsAccount::findOrFail($id);
            $debtor->decrement('balance', $request->payment_amount);
        });

        return response()->json(['success' => true, 'message' => 'Payment recorded successfully!']);
    }

    public function AdminDebtorsAddBatch(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.products_id' => 'nullable|exists:products,id',
            'items.*.custom_entry' => 'nullable|string',
            'items.*.custom_price' => 'nullable|numeric',
            'items.*.custom_cost' => 'nullable|numeric',
            'items.*.quantity' => 'required|numeric|min:0.1',
        ]);

        $batchNumber = 'DEBT-' . strtoupper(Str::random(8));
        $totalDebt = 0;

        DB::transaction(function () use ($id, $request, $batchNumber, &$totalDebt) {
            foreach ($request->items as $item) {
                $price = 0;
                if (!empty($item['products_id'])) {
                    $product = Products::findOrFail($item['products_id']);
                    $price = $product->selling_price;

                    // Decrement product quantity
                    $product->decrement('quantity', $item['quantity']);
                } else {
                    $price = $item['custom_price'] ?? 0;
                }

                DebtorsItems::create([
                    'batch_number' => $batchNumber,
                    'debtor_id' => $id,
                    'products_id' => $item['products_id'] ?? null,
                    'custom_entry' => $item['custom_entry'] ?? null,
                    'custom_price' => $item['custom_price'] ?? null,
                    'custom_cost' => $item['custom_cost'] ?? null,
                    'quantity' => $item['quantity'],
                ]);

                $totalDebt += ($price * $item['quantity']);
            }

            $debtor = DebtorsAccount::findOrFail($id);
            $debtor->increment('balance', $totalDebt);
        });

        return response()->json(['success' => true, 'message' => 'Items added to debt successfully!']);
    }

    public function AdminDebtorsDelete($id)
    {
        $debtor = DebtorsAccount::findOrFail($id);

        // Check if there's any balance
        if ($debtor->balance > 0) {
            return redirect()->back()->with('error', 'Cannot delete account with an outstanding balance!');
        }

        // Check if there's any transaction history
        $hasTransactions = DebtorsTransactions::where('debtor_id', $id)->exists();
        $hasItems = DebtorsItems::where('debtor_id', $id)->exists();

        if ($hasTransactions || $hasItems) {
            return redirect()->back()->with('error', 'Cannot delete account with transaction history!');
        }

        $debtor->delete();

        return redirect()->back()->with('success', 'Debtor account deleted successfully!');
    }
}
