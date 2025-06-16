<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});


// Public routes (Login/Register)
Route::get('/login', [AuthController::class, 'showlogin'])->name('login');
Route::get('/register', [AuthController::class, 'showregister'])->name('register');
Route::post('/register', [AuthController::class, 'storeregister'])->name('store.registers');
Route::post('/login', [AuthController::class, 'storelogin'])->name('store.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes 
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

//Reset Password Routes
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard.view');
    Route::post('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::post('/dashboard/monthly-chart', [DashboardController::class, 'getMonthlyChartData'])->name('dashboard.monthlyChart');


    // Account 
    Route::get('/accounts/view', [AccountController::class, 'account'])->name('accounts.view');
    Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('/accounts/store', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('accounts/{id}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::put('accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    // Route::get('/accounts/data', [AccountController::class, 'getData'])->name('accounts.data');



    //Income
    Route::get('/income/view', [IncomeController::class, 'income'])->name('income.view');
    Route::get('/income/create', [IncomeController::class, 'create'])->name('income.create');
    Route::post('/income/store', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/income/{id}/edit', [IncomeController::class, 'edit'])->name('income.edit');
    Route::put('/income/{id}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/income/{id}', [IncomeController::class, 'destroy'])->name('income.destroy');  


    //Expense
    Route::get('/expense/view', [ExpenseController::class, 'expense'])->name('expense.view');
    Route::get('/expense/create', [ExpenseController::class, 'create'])->name('expense.create');
    Route::post('/expense/store', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('/expense/{id}/edit', [ExpenseController::class, 'edit'])->name('expense.edit');
    Route::put('/expense/{id}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/expense/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');  

});



// // Income Routes
// Route::controller(IncomeController::class)->prefix('income')->group(function () {
//     Route::get('/', 'index')->name('income.index');
//     Route::get('/create', 'create')->name('income.create');
//     Route::post('/store', 'store')->name('income.store');
//     Route::get('/edit/{id}', 'edit')->name('income.edit');
//     Route::post('/update/{id}', 'update')->name('income.update');
//     Route::delete('/delete/{id}', 'destroy')->name('income.destroy');
// });

// // Expense Routes
// Route::controller(ExpenseController::class)->prefix('expense')->group(function () {
//     Route::get('/', 'index')->name('expense.index');
//     Route::get('/create', 'create')->name('expense.create');
//     Route::post('/store', 'store')->name('expense.store');
//     Route::get('/edit/{id}', 'edit')->name('expense.edit');
//     Route::post('/update/{id}', 'update')->name('expense.update');
//     Route::delete('/delete/{id}', 'destroy')->name('expense.destroy');
// });