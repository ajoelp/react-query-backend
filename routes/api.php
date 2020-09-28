<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Reports;
use Illuminate\Support\Facades\Route;

Route::apiResource('expenses', ExpenseController::class);
Route::get('reports', Reports::class)->name('reports');
