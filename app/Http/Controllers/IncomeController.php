<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Account;


class IncomeController extends Controller
{
    
    public function income(Request $request)
    {
        if ($request->ajax()) {
            $data = Income::with('account')->latest()->get();
            return response()->json($data);
        }

        $incomes = Income::with('account')->latest()->get(); // Get incomes with their account details
        return view('pages.income', compact('incomes')); // Pass data to the view
    }

    public function create()
    {
        $accounts = Account::all();
        return view('pages.create_income', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        Income::create($validated);
        return response()->json(['message' => 'Income Details created successfully.']);
    }

    public function edit($id)
    {
        $income = Income::findOrFail($id);
        $accounts = Account::all();
        return view('pages.create_income', compact('income', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $income = Income::findOrFail($id);
        $income->update($validated);
        return response()->json(['message' => 'Income Details updated successfully.']);
    }

    public function destroy($id)
    {
        Income::findOrFail($id)->delete();
        return response()->json(['message' => 'Income Details successfully.']);
    }
    
    
}
