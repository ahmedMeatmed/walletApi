<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Services\TransferService;
use App\Http\Requests\P2PTransferRequest;
use App\Http\Requests\ChargeWalletRequest;

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

    public function confirm($transaction, TransferService $service)
    {
        $transaction = Transaction::findOrFail($transaction);

        $service->confirm($transaction);

        return response()->json(['message' => 'Transfer confirmed']);
    }

    public function cancel($transaction, TransferService $service)
    {
        $transaction = Transaction::findOrFail($transaction);

        $service->cancel($transaction);

        return response()->json(['message' => 'Transfer cancelled']);
    }

}
