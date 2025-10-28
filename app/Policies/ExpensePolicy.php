<?php
// app/Policies/ExpensePolicy.php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Expense $expense)
    {
        return $user->isManager() || $expense->user_id === $user->id;
    }

    public function update(User $user, Expense $expense)
    {
        return $user->isManager() && $expense->status === 'pending';
    }

    public function delete(User $user, Expense $expense)
    {
        return $expense->user_id === $user->id && $expense->status === 'pending';
    }
}
