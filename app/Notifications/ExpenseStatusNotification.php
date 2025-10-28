<?php
// app/Notifications/ExpenseStatusNotification.php

namespace App\Notifications;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Expense $expense)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $status = $this->expense->status;
        $subject = "Expense {$status}: {$this->expense->title}";

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line("Your expense '{$this->expense->title}' has been {$status}.")
            ->line("Amount: \$" . number_format($this->expense->amount, 2))
            ->line("Date: {$this->expense->date->format('M d, Y')}")
            ->when($status === 'rejected', function ($mail) {
                return $mail->line("Reason: {$this->expense->rejection_reason}");
            })
            ->action('View Expense', url("/expenses/{$this->expense->id}"))
            ->line('Thank you for using our expense system!');
    }
}
