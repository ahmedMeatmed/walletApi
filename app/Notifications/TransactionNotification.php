<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct($transaction)
    {
        //
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Transaction Notification')
            ->line($this->buildMessage())
            ->line('Amount: ' . $this->transaction->amount)
            // ->action('View Transactions', url('/transactions'))
            ->line('Thank you for using our wallet.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'type' => $this->transaction->type,
            'amount' => $this->transaction->amount,
            'status' => $this->transaction->status,
            'message' => $this->buildMessage(),
        ];
    }

    protected function buildMessage()
    {
        return match ($this->transaction->type) {
            'charge' => 'Wallet charged successfully',
            'transfer' => 'Transfer completed successfully',
            'service_purchase' => 'Service purchased successfully',
            default => 'New transaction update',
        };
    }
}
