@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Expenses</h1>
        @auth
            @if(auth()->user()->isEmployee())
                <a href="{{ route('expenses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Submit New Expense
                </a>
            @endif
        @endauth
    </div>

    @if($expenses->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg">No expenses found.</p>
            @if(auth()->user()->isEmployee())
                <a href="{{ route('expenses.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Submit Your First Expense
                </a>
            @endif
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        @if(auth()->user()->isManager())
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($expenses as $expense)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $expense->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($expense->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $expense->category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $expense->date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($expense->status === 'approved') bg-green-100 text-green-800
                                    @elseif($expense->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>
                            @if(auth()->user()->isManager())
                                <td class="px-6 py-4 whitespace-nowrap">{{ $expense->user->name }}</td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('expenses.show', $expense) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                @if($expense->receipt_path)
                                    <a href="{{ route('expenses.download', $expense) }}" class="text-green-600 hover:text-green-900">Receipt</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    @endif
</div>
@endsection
