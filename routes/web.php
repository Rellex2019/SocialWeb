<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckChatPermission;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/check_auth', [AuthController::class, 'isAuth']);
Route::post('/check/login_availability', [AuthController::class, 'isLoginAvailability']);

//ПОСТы без авторизации
Route::get('/post/get_posts', [PostController::class, 'getPosts']);
//Категори без авторизации
Route::get('/get/categories', [PostController::class, 'getCategories']);
//Комментарии
Route::get('post/{id}/comment', [PostController::class, 'getComment']);

//- нужно авторизоваться--------------Добавить потом в middleware группу
Route::middleware('auth:sanctum')->group(function () {

    //Смена логина
    Route::patch('/user/login/edit', [AuthController::class, 'changeLogin']);
    //Смена пароля
    Route::patch('/user/password/edit', [AuthController::class, 'changePassword']);

    Route::post('/user/info/edit', [UserController::class, 'changeUserInfo']);

    Route::get('/user/info/{id}', [AuthController::class, 'getUser']);

    Route::delete('/user/account/delete', [AuthController::class, 'deleteAccount']);



    //Друзья 
    Route::get('/friend', [FriendController::class, 'getFriend']);
    Route::get('/people', [FriendController::class, 'getPeople']);
    Route::get('/friend/get_friend_request', [FriendController::class, 'getFriendRequest']);
    Route::get('/friend/get_user_request', [FriendController::class, 'getUserRequest']);
    Route::post('/friend/send_friend_request', [FriendController::class, 'storeFriendRequest']);
    Route::post('/friend/delete_friend_request', [FriendController::class, 'deleteFriendRequest']);
    Route::delete('/friend/{id}/delete', [FriendController::class, 'deleteFriend']);

    //Сообщения
    // Route::middleware(CheckChatPermission::class)->group(function (){
        Route::post('/chat/{id}/message/send', [MessageController::class, 'storeMessage']);
        Route::delete('/chat/{id}/message/delete', [MessageController::class, 'deleteMessage']);

        Route::post('/chat/{friendId}/message', [MessageController::class, 'openChat']);
    
        Route::get('/chat/{id}/messages', [MessageController::class, 'getMessage']);
        Route::get('/chat/users/get', [MessageController::class, 'getChats']);
    // });
    //---------------------------------

    //категории
    Route::patch('/user/category/change', [PostController::class, 'changeCategories']);
    Route::get('/user/category', [PostController::class, 'getUserCategories']);

    //Посты
    Route::get('/posts/user/{id}', [PostController::class, 'getUserPosts']);
    Route::post('/post/create', [PostController::class, 'storePost']);
    Route::delete('/post/user/delete/{id}', [PostController::class, 'deleteMyPost']);
    Route::patch('/post/user/change/{id}', [PostController::class, 'changeMyPost']);
    //Лайки
    Route::post('/post/{id}/like', [PostController::class, 'toggleLike']);
    //Комментарии - надо сделать
    Route::post('post/{id}/comment', [PostController::class, 'storeComment']);
});


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
