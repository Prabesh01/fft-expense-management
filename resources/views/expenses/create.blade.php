@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Submit New Expense</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description *</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount *</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category *</label>
                    <select id="category" name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select Category</option>
                        <option value="travel" {{ old('category') == 'travel' ? 'selected' : '' }}>Travel</option>
                        <option value="meals" {{ old('category') == 'meals' ? 'selected' : '' }}>Meals</option>
                        <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="entertainment" {{ old('category') == 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date *</label>
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           max="{{ date('Y-m-d') }}" required>
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="receipt" class="block text-gray-700 text-sm font-bold mb-2">Receipt (Optional)</label>
                    <input type="file" id="receipt" name="receipt" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           accept=".jpg,.jpeg,.png,.pdf">
                    @error('receipt')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (Max: 10MB)</p>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('expenses.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Submit Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
