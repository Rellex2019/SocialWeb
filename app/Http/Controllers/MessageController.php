<?php

namespace App\Http\Controllers;

use App\Events\MessageSend;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function openChat(Request $request, $friendId)
    {
        $user = $request->user();
        $chatExists = Chat::where('user_id', $user->id)
        ->where('friend_id', $friendId)
        ->orWhere('user_id', $friendId)
        ->where('friend_id', $user->id)->get();

    if (!$chatExists) {
        $newChat = Chat::create([
            'name' => $user->userInfo->name.' '.$user->userInfo->surname,
            'user_id' => $user->id,
            'friend_id' => $friendId
        ]);
        return response()->json(['message' => 'Чат создан']);
    }
    else{
        return response()->json(['message' => 'Чат существует']);
    }

    }
    public function storeMessage(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:204800'
        ]);
        if (!User::find($id)) {
            return response()->json(['message' => 'Пользователь не найден.'], 404);
        }


        $user = $request->user();

        $chatExists = Chat::where('user_id', $user->id)
            ->where('friend_id', $id)
            ->orWhere('user_id', $id)
            ->where('friend_id', $user->id)->first();

        if ($chatExists) {
            $message = [
                'chat_id' => $chatExists->id,
                'content' => $request->content
            ];
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads', 'public');
                $message['file_path'] = $path;
            }
            $messageData = Message::create($message);


            event(new MessageSend($messageData));


            return response()->json([
                'message' => 'Сообщение отправлено',
                'data' => $messageData,
            ]);
        } else {
            $newChat = Chat::create([
                'name' => $user->userInfo->name.' '.$user->userInfo->surname,
                'user_id' => $user->id,
                'friend_id' => $id
            ]);

            $message = [
                'chat_id' => $newChat->id,
                'content' => $request->content
            ];
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads', 'public');
                $message['file_path'] = $path;
            }
            $messageData = Message::create($message);
            event(new MessageSend($messageData));
            return response()->json([
                'message' => 'Чат создан, сообщение отправлено',
                'data' => $messageData,
            ]);
        }
    }
    public function getMessage(Request $request, $id)
    {
        // $messages = $request->user()->allMessages()
        //     ->where(function ($query) use ($request) {
        //         $query->where('friend_id', $request->id)
        //             ->orWhere('user_id', $request->id);
        //     })
        //     ->orderBy('created_at', 'asc')
        //     ->get();
        $user = $request->user();
        $messages = $user->chat()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($messages);
    }
}
