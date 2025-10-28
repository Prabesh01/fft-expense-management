<?php
// routes/api.php

use App\Http\Controllers\Api\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // Expense routes
    Route::apiResource('expenses', ExpenseController::class);
    
    // Additional expense routes
    Route::get('user/expenses', [ExpenseController::class, 'index']);
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve']);
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject']);
});
