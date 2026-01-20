<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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

}
