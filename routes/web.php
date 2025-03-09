<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/check_auth', [AuthController::class, 'isAuth']);

Route::get('/{any}', function () {
    return view('app'); 
})->where('any', '.*');