<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\UserController;


Route::post('users',[UserController::class,'store']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('users', UserController::class)->except(['store']);
  
        Route::post('/logout', [AuthController::class, 'logout']);
});