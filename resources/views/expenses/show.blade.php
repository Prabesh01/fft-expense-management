@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Expense Details</h1>
        <a href="{{ route('expenses.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold">{{ $expense->title }}</h2>
                    <p class="text-gray-600">{{ $expense->description }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($expense->status === 'approved') bg-green-100 text-green-800
                    @elseif($expense->status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($expense->status) }}
                </span>
            </div>
        </div>

        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Basic Information</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="text-lg font-semibold">${{ number_format($expense->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="capitalize">{{ $expense->category }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd>{{ $expense->date->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Submitted By</dt>
                        <dd>{{ $expense->user->name }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Status Information</h3>
                <dl class="space-y-2">
                    @if($expense->status !== 'pending')
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Reviewed By</dt>
                            <dd>{{ $expense->reviewer->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Reviewed At</dt>
                            <dd>{{ $expense->reviewed_at ? $expense->reviewed_at->format('F d, Y g:i A') : 'N/A' }}</dd>
                        </div>
                        @if($expense->status === 'rejected' && $expense->rejection_reason)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                                <dd class="text-red-600">{{ $expense->rejection_reason }}</dd>
                            </div>
                        @endif
                    @else
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-yellow-600">Pending Review</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        @if($expense->receipt_path)
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-2">Receipt</h3>
                <a href="{{ route('expenses.download', $expense) }}" 
                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Receipt
                </a>
            </div>
        @endif

        @if(auth()->user()->isManager() && $expense->status === 'pending')
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-700 mb-4">Review Expense</h3>
                <form method="POST" action="{{ route('expenses.update', $expense) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="toggleRejectionReason()">
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>

                    <div id="rejectionReason" style="display: none;">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Please provide a reason for rejection...">{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Submit Review
                    </button>
                </form>
            </div>

            <script>
                function toggleRejectionReason() {
                    const status = document.getElementById('status').value;
                    const rejectionReason = document.getElementById('rejectionReason');
                    rejectionReason.style.display = status === 'rejected' ? 'block' : 'none';
                }
            </script>
        @endif
    </div>
</div>
@endsection
