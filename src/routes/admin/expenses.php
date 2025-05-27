<?php

use XelentAbrar\HospitalExpense\Http\Controllers\Expenses\ExpenseController;
use Illuminate\Support\Facades\Route;
use XelentAbrar\HospitalExpense\Http\Controllers\Expenses\ReportController;


Route::resource('expenses', ExpenseController::class)->names('expenses');

Route::get('expense/report', [ReportController::class, 'expenseReport'])->name('expense.report');