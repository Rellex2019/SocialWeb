<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/check_auth', [AuthController::class, 'isAuth']);
Route::post('/check/login_availability', [AuthController::class, 'isLoginAvailability']);



//Друзья
Route::get('/friend', [FriendController::class, 'getFriend']);
Route::get('/people', [FriendController::class, 'getPeople']);
Route::get('/friend/get_friend_request', [FriendController::class, 'getFriendRequest']);
Route::get('/friend/get_user_request', [FriendController::class, 'getUserRequest']);
Route::post('/friend/send_friend_request', [FriendController::class, 'storeFriendRequest']);
Route::post('/friend/delete_friend_request', [FriendController::class, 'deleteFriendRequest']);
Route::post('/friend/delete_friend', [FriendController::class, 'deleteFriend']);


//Удалить
Route::post('/friend/addFriend', [FriendController::class, 'storeFriend']);

Route::get('/{any}', function () {
    return view('app'); 
})->where('any', '.*');