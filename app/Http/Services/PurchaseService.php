<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Notifications\TransactionNotification;


class PurchaseService
{
     public function purchase(User $user, Service $service)
    {
        DB::transaction(function () use ($user, $service) {

            $wallet = $user->wallet()->lockForUpdate()->first();

            if ($wallet->balance < $service->price) {
                throw new \Exception('Insufficient balance');
            }

            $wallet->decrement('balance', $service->price);

            $transaction = Transaction::create([

                'from_user_id' => $user->id,
                'type' => 'service_purchase',
                'status' => 'completed',
                'amount' => $service->price,
                'reference_id' => $service->id,
                'reference_type' => Service::class,
            ]);

            $user->notify(new TransactionNotification($transaction));
        });
}
}