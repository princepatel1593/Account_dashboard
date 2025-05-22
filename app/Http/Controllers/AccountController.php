<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Account;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;



class AccountController extends Controller
{
    //show list page
    public function account(){
        $accounts = Account::all();
        return view('pages.accounts', compact('accounts'));
    }
    
    // Show create form
    public function create(){
        return view('pages.create_account');
    }

     // Store account
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'account_name' => 'required|string|max:255',
            'opening_balance' => 'required|numeric',
        ]);

        // Store to database
        Account::create([
            'account_name' => $request->account_name,
            'opening_balance' => $request->opening_balance,
        ]);

        // Redirect or response
        return redirect()->route('accounts.view')->with('success', 'Account created successfully!');
    }
    // In AccountController.php

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        return view('pages.create_account', compact('account')); // Show the edit form with the current account data
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        
        // Validate data
        $request->validate([
            'account_name' => 'required|max:255',
            'opening_balance' => 'required|numeric',
        ]);

        // Update account data
        $account->update([
            'account_name' => $request->account_name,
            'opening_balance' => $request->opening_balance,
        ]);

        return response()->json(['message' => 'Account updated successfully']);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
    // public function getData(Request $request)
    // {
    //     $accounts = Account::query();
    //      dd($accounts->get());
    //     return DataTables::of($accounts)
    //         ->addColumn('action', function ($account) {
    //             return '
    //                 <a href="' . route('accounts.edit', $account->id) . '" class="btn btn-info">Edit</a>
    //                 <button class="btn btn-danger delete-account" data-id="' . $account->id . '">Delete</button>
    //             ';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }

}
