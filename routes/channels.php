<?php

use App\Broadcasting\ChatChannel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatId}', ChatChannel::class);


Broadcast::channel('comments.{PostId}', function($user){
    return Auth::check();
});
