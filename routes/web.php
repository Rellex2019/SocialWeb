<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/check_auth', [AuthController::class, 'isAuth']);
Route::post('/check/login_availability', [AuthController::class, 'isLoginAvailability']);


//- нужно авторизоваться--------------Добавить потом в middleware группу
//Друзья 
Route::get('/friend', [FriendController::class, 'getFriend']);
Route::get('/people', [FriendController::class, 'getPeople']);
Route::get('/friend/get_friend_request', [FriendController::class, 'getFriendRequest']);
Route::get('/friend/get_user_request', [FriendController::class, 'getUserRequest']);
Route::post('/friend/send_friend_request', [FriendController::class, 'storeFriendRequest']);
Route::post('/friend/delete_friend_request', [FriendController::class, 'deleteFriendRequest']);
Route::delete('/friend/delete_friend', [FriendController::class, 'deleteFriend']);


//Удалить
Route::post('/friend/addFriend', [FriendController::class, 'storeFriend']);
//---------------------------------

//Посты
Route::get('/post/my_posts', [PostController::class, 'getMyPosts']);
Route::get('/post/get_posts', [PostController::class, 'getPosts']);
Route::post('/post/create', [PostController::class, 'storePost']);
Route::delete('/post/user/delete/{id}', [PostController::class, 'deleteMyPost']);
Route::patch('/post/user/change/{id}', [PostController::class, 'changeMyPost']);


Route::get('/{any}', function () {
    return view('app'); 
})->where('any', '.*');