<?php
namespace App\Http\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransferService
{
      public function create(User $sender, User $receiver, float $amount)
    {
        if ($sender->id === $receiver->id) {
            throw new \Exception("Cannot transfer to yourself");
        }

        return Transaction::create([
            'from_user_id' => $sender->id,
            'to_user_id' => $receiver->id,
            'type' => 'transfer',
            'status' => 'pending',
            'amount' => $amount,
        ]);
    }

     public function confirm($transaction)
    {

        DB::transaction(function () use ($transaction) {


            $senderWallet = User::where('id', $transaction->from_user_id)
                        ->lockForUpdate()
                        ->first()
                        ->wallet;

    $receiverWallet = User::where('id', $transaction->to_user_id)
                          ->lockForUpdate()
                          ->first()
                          ->wallet;

            if ($senderWallet->balance < $transaction->amount) {
                throw new \Exception("Insufficient balance");
            }

            $senderWallet->decrement('balance', $transaction->amount);
            $receiverWallet->increment('balance', $transaction->amount);

            $transaction->status = 'completed';
            $transaction->save();

           
        });
    }

     public function cancel($transaction)
    {
        if ($transaction->status !== 'pending') {
            throw new \Exception("Cannot cancel a completed or cancelled transaction");
        }

        $transaction->status = 'cancelled';
        $transaction->save();

    }
}