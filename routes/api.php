<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::apiResource('todos', TodoController::class);
    });
});
