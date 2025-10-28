<?php
// app/Console/Commands/SendMonthlyExpenseSummaries.php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\MonthlyExpenseSummaryNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendMonthlyExpenseSummaries extends Command
{
    protected $signature = 'expenses:send-monthly-summaries';
    protected $description = 'Send monthly expense summaries to all employees';

    public function handle()
    {
        $now = Carbon::now();
        $lastMonth = $now->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $employees = User::where('role', 'employee')->get();

        foreach ($employees as $employee) {
            $expenses = $employee->getMonthlyApprovedExpenses($year, $month);
            $totalAmount = $expenses->sum('amount');

            if ($expenses->isNotEmpty()) {
                $employee->notify(new MonthlyExpenseSummaryNotification(
                    $employee,
                    $expenses,
                    $totalAmount,
                    $month,
                    $year
                ));
            }
        }

        $this->info("Monthly expense summaries sent to {$employees->count()} employees.");
    }
}
