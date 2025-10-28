<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isEmployee();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01|max:100000',
            'category' => 'required|in:travel,meals,equipment,entertainment,other',
            'date' => 'required|date|before_or_equal:today',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ];
    }
}
