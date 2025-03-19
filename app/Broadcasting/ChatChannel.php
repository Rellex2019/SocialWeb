<?php

namespace App\Broadcasting;

use App\Models\Chat;
use App\Models\User;

class ChatChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, $chatId): array|bool
    {
        $chat = Chat::find($chatId);
        if(!$chat)
        {
            return false;
        }
        if($user->id == $chat->user_id || $user->id == $chat->friend_id)
        {
            return true;
        }
        else{
            return false;
        }
    }
}
