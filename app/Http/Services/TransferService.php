<?php
namespace App\Http\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Notifications\TransactionNotification;
use Illuminate\Support\Facades\DB;

class TransferService
{
      public function create(User $sender, User $receiver, float $amount)
    {

        if ($sender->id === $receiver->id) {
            throw new \Exception("Cannot transfer to yourself");
        }

        $transaction = Transaction::create([
            'from_user_id' => $sender->id,
            'to_user_id' => $receiver->id,
            'type' => 'transfer',
            'status' => 'pending',
            'amount' => $amount,
        ]);

        $sender->notify(new TransactionNotification($transaction));

        return $transaction;
    }

     public function confirm($transaction)
    {

        DB::transaction(function () use ($transaction) {

        $sender = User::where('id', $transaction->from_user_id);
        $receiver = User::where('id', $transaction->to_user_id);

            $senderWallet = $sender->lockForUpdate()->first()->wallet;

            $receiverWallet = $receiver->lockForUpdate()->first()->wallet;

            if ($senderWallet->balance < $transaction->amount) {
                throw new \Exception("Insufficient balance");
            }

            $senderWallet->decrement('balance', $transaction->amount);
            $receiverWallet->increment('balance', $transaction->amount);

            $transaction->status = 'completed';
            $transaction->save();
           
            $sender->first()->notify(new TransactionNotification($transaction));
            $receiver->first()->notify(new TransactionNotification($transaction));
        });
        
    }

     public function cancel($transaction,$sender)
    {
        if ($transaction->status !== 'pending') {
            throw new \Exception("Cannot cancel a completed or cancelled transaction");
        }

        $transaction->status = 'cancelled';

        $transaction->save();

        $sender->notify(new TransactionNotification($transaction));


    }
}