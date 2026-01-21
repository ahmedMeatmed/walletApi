<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelTransactionRequest;
use App\Http\Services\TransferService;
use App\Http\Requests\P2PTransferRequest;
use App\Http\Requests\ChargeWalletRequest;
use App\Http\Requests\ConfirmTransactionRequest;
use App\Notifications\TransactionNotification;
use Symfony\Component\HttpFoundation\Request;

class WalletController extends Controller
{
    //
    public function charge(ChargeWalletRequest $request)
    {
        $user = $request->user();

        DB::transaction(
            
        function() use ($user, $request) {

         $user->wallet->increment('balance',  $request->amount);

            $transaction = Transaction::create([
                'from_user_id' => null,
                'to_user_id' => $user->id,
                'type' => 'charge',
                'status' => 'completed',
                'amount' => $request->amount,
            ]);

            $user->notify(new TransactionNotification($transaction));

              return response()->json([
            'message' => 'Wallet charged successfully',
            'balance' => $user->wallet->balance
            ]);
   
        }
        );
    }


    public function transfer(P2PTransferRequest $request, TransferService $service)
    {
    
        $transaction = $service->create($request->user(), User::find($request->receiver_id), (float)$request->amount);

        return response()->json([
            'message' => 'Transfer created, pending confirmation',
            'transaction_id' => $transaction->id
        ]);
    }

    public function confirm(TransferService $service,ConfirmTransactionRequest $request)
    {

        $transaction = Transaction::findOrFail($request->transaction);

        $service->confirm($transaction);

        return response()->json(['message' => 'Transfer confirmed']);
    }

    public function cancel(CancelTransactionRequest $request, TransferService $service)
    {

        $transaction = Transaction::findOrFail($request->transaction);
        $sender = $request->user();

        $service->cancel($transaction,$sender);

        return response()->json(['message' => 'Transfer cancelled']);
    }

}
