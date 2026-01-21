<?php
namespace App\Http\Services;

use App\Models\Transaction;
use Symfony\Component\HttpFoundation\Request;

class TransactionFilterService{

    public function filter($request){

          $transactions = Transaction::query()
            ->where(function ($q) use ($request) {
                $q->where('from_user_id', $request->user()->id)
                  ->orWhere('to_user_id', $request->user()->id);
            })
            ->when($request->type, fn ($q) =>
                $q->where('type', $request->type)
            )
            ->when($request->status, fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->from_date, fn ($q) =>
                $q->whereDate('created_at', '>=', $request->from_date)
            )
            ->when($request->to_date, fn ($q) =>
                $q->whereDate('created_at', '<=', $request->to_date)
            )
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($transactions);
    }
}