<?php

namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    //
    public function dashboard(){
        $accounts = Account::all();
        return view('pages.dashboard', compact('accounts'));
    }

    public function getStats(Request $request)
    {
        $accountId = $request->account_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Example values
        $openingBalance = Account::find($accountId)->opening_balance ?? 0;

        $totalIncome = Income::where('account_id', $accountId)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('amount');

        $totalExpense = Expense::where('account_id', $accountId)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('amount');

        $totalBalance = $openingBalance + $totalIncome - $totalExpense;

        return response()->json([
            'opening_balance' => number_format($openingBalance, 2),
            'total_income' => number_format($totalIncome, 2),
            'total_expense' => number_format($totalExpense, 2),
            'total_balance' => number_format($totalBalance, 2)
        ]);
    }

public function getMonthlyChartData(Request $request)
{
    $accountId = $request->input('account_id');
    $financialYear = (int)$request->input('financial_year');

    $startDate = "$financialYear-04-01";
    $endDate = ($financialYear + 1) . '-03-31';

    // Month labels from Apr to Mar
    $months = ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'];

    // Map month abbreviations to month number for querying
    $monthNumbers = [
        'Apr' => 4, 'May' => 5, 'Jun' => 6, 'Jul' => 7,
        'Aug' => 8, 'Sep' => 9, 'Oct' => 10, 'Nov' => 11,
        'Dec' => 12, 'Jan' => 1, 'Feb' => 2, 'Mar' => 3,
    ];

    try {
        // Get incomes grouped by month
        $incomeData = DB::table('incomes')
            ->selectRaw("MONTH(date) as month_number, SUM(amount) as total")
            ->where('account_id', $accountId)
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month_number')
            ->orderBy('month_number')
            ->get()
            ->keyBy('month_number');

        // Get expenses grouped by month (assuming expenses table has same structure)
        $expenseData = DB::table('expenses')
            ->selectRaw("MONTH(date) as month_number, SUM(amount) as total")
            ->where('account_id', $accountId)
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month_number')
            ->orderBy('month_number')
            ->get()
            ->keyBy('month_number');

        $income = [];
        $expense = [];

        foreach ($months as $month) {
            $num = $monthNumbers[$month];
            // Handle year wrap-around for Jan-Mar
            // Since your financial year spans two calendar years,
            // data for Jan-Mar belongs to next calendar year, so month_number is 1-3
            $income[] = isset($incomeData[$num]) ? (float)$incomeData[$num]->total : 0;
            $expense[] = isset($expenseData[$num]) ? (float)$expenseData[$num]->total : 0;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'months' => $months,
                'income' => $income,
                'expense' => $expense,
            ],
        ]);

    } catch (\Exception $e) {
        \Log::error('Chart Error: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch monthly chart data',
            'error' => $e->getMessage(),
        ], 500);
    }
}






    
}
