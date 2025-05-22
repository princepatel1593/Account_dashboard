<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Expense;

class ExpenseController extends Controller
{
    //
     //show list page
    public function expense(){
        $expenses = Expense::with('account')->latest()->get();
        return view('pages.expense', compact('expenses'));
    }
    
    // Show create form
     public function create()
    {
        $accounts = Account::all();
        return view('pages.create_expense', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'title' => 'required|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        Expense::create($request->all());

        return response()->json(['message' => 'Expense added successfully!']);
    }
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $accounts = Account::all();
        return view('pages.create_expense', compact('expense', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return response()->json(['message' => 'Expense Details updated successfully']);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Expense Details deleted successfully']);
    }
}
