<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Services\TransactionFilterService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function index(TransactionFilterService $service,Request $request)
    {
        return $service->filter($request);
    }
}
