<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseUpdateRequest extends FormRequest
{
    public function authorize()
    {
        $expense = $this->route('expense');
        
        return auth()->check() && 
               auth()->user()->isManager() && 
               $expense->status === 'pending';
    }

    public function rules()
    {
        return [
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->status === 'rejected' && empty($this->rejection_reason)) {
                $validator->errors()->add('rejection_reason', 'The rejection reason field is required when status is rejected.');
            }
            
            // For approved status, ensure rejection_reason is null or empty
            if ($this->status === 'approved' && !empty($this->rejection_reason)) {
                $this->merge(['rejection_reason' => null]);
            }
        });
    }
}
