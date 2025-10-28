<?php
// app/Http/Controllers/Api/ExpenseController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Models\Expense;
use App\Notifications\ExpenseStatusNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isManager()) {
            $expenses = Expense::with(['user', 'reviewer'])
                ->latest()
                ->paginate(15);
        } else {
            $expenses = $user->expenses()
                ->with('reviewer')
                ->latest()
                ->paginate(15);
        }

        return response()->json($expenses);
    }

    public function store(ExpenseStoreRequest $request): JsonResponse
    {
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = Expense::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'date' => $request->date,
            'receipt_path' => $receiptPath,
        ]);

        return response()->json([
            'message' => 'Expense submitted successfully!',
            'expense' => $expense->load('user')
        ], 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        $this->authorize('view', $expense);
        return response()->json($expense->load(['user', 'reviewer']));
    }

    public function update(ExpenseUpdateRequest $request, Expense $expense): JsonResponse
    {
        $this->authorize('update', $expense);

        $expense->update([
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $expense->user->notify(new ExpenseStatusNotification($expense));

        return response()->json([
            'message' => "Expense {$request->status} successfully!",
            'expense' => $expense->load(['user', 'reviewer'])
        ]);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $this->authorize('delete', $expense);

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully!']);
    }
}
