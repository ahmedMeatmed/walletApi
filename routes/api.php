<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\WalletController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\UserController;


Route::post('users',[UserController::class,'store']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('users', UserController::class)->except(['store']);

        Route::post('/wallet/charge', [WalletController::class, 'charge']);

        Route::post('/transfer', [WalletController::class, 'transfer']);

        Route::post('/transfer/{transaction}/confirm', [WalletController::class, 'confirm']);

        Route::post('/transfer/{transaction}/cancel', [WalletController::class, 'cancel']);
  
        Route::post('/logout', [AuthController::class, 'logout']);
});