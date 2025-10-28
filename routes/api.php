<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::prefix('v1')->group(function () {
Route::get('/todo', function () {});
Route::post('/todo', function () {});
Route::patch('/todo', function () {});
Route::delete('/todo', function () {});
});
