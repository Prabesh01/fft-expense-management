<?php
// app/Notifications/MonthlyExpenseSummaryNotification.php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlyExpenseSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public $expenses,
        public $totalAmount,
        public $month,
        public $year
    ) {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $monthName = \Carbon\Carbon::create($this->year, $this->month)->format('F Y');

        $mail = (new MailMessage)
            ->subject("Monthly Expense Summary - {$monthName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Here is your expense summary for {$monthName}:")
            ->line("Total Approved Expenses: $" . number_format($this->totalAmount, 2))
            ->line("Number of Expenses: " . $this->expenses->count());

        if ($this->expenses->isNotEmpty()) {
            $mail->line('')
                ->line('Detailed Breakdown:');

            foreach ($this->expenses->groupBy('category') as $category => $categoryExpenses) {
                $categoryTotal = $categoryExpenses->sum('amount');
                $mail->line("- {$category}: $" . number_format($categoryTotal, 2));
            }

            $mail->line('')
                ->action('View All Expenses', url('/expenses'));
        }

        return $mail->line('Thank you for using our expense system!');
    }
}
