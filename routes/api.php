<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
Route::get('/todo', function () {});
Route::post('/todo', function () {});
Route::patch('/todo', function () {});
Route::delete('/todo', function () {});
});
