<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
     
        $transaction = $this->route('transaction');
        $transaction = Transaction::find($transaction);
        return $this->user()->can('confirm',$transaction);
    }  

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
