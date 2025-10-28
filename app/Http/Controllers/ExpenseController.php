<?php
// app/Http/Controllers/ExpenseController.php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Notifications\ExpenseStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isManager()) {
            $expenses = Expense::with('user')
                ->latest()
                ->paginate(15);
        } else {
            $expenses = $user->expenses()
                ->latest()
                ->paginate(15);
        }

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(ExpenseStoreRequest $request)
    {
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = Expense::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'date' => $request->date,
            'receipt_path' => $receiptPath,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense submitted successfully!');
    }

    public function show(Expense $expense)
    {
        // Load relationships
        $expense->load(['user', 'reviewer']);
        
        return view('expenses.show', compact('expense'));
    }


public function update(Request $request, Expense $expense)
{
    // Manual authorization
    if (!auth()->user()->isManager()) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    if ($expense->status !== 'pending') {
        return redirect()->back()->with('error', 'Can only update pending expenses.');
    }

    // Manual validation
    $request->validate([
        'status' => 'required|in:approved,rejected',
    ]);

    // Additional validation for rejection reason
    if ($request->status === 'rejected') {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);
    }

    // Update the expense
    $expense->update([
        'status' => $request->status,
        'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    // Send notification to employee
    $expense->user->notify(new ExpenseStatusNotification($expense));

    return redirect()->route('expenses.show', $expense)
        ->with('success', "Expense {$request->status} successfully!");
}

    public function downloadReceipt(Expense $expense)
    {
        if (!$expense->receipt_path) {
            abort(404);
        }

        return Storage::disk('public')->download($expense->receipt_path);
    }
}
