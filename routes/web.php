<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Remove or comment out this line:
// Auth::routes();

// Manual authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('home');
    Route::resource('expenses', ExpenseController::class)->except(['edit']);
    Route::get('expenses/{expense}/download', [ExpenseController::class, 'downloadReceipt'])
        ->name('expenses.download');
});
