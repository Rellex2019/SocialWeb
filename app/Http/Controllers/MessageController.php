<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
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
        $message = [
            'user_id'=> $user->id,
            'friend_id' => $request->id,
            'content'=> $request->content
        ];
        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');
            $message['file_path']= $path;
        }
        $messageData = Message::create($message);
        return response()->json([
            'message' => 'Сообщение отправлено',
            'data' => $messageData,
        ]);
    }
    public function getMessage(Request $request)
    {
        $messages = $request->user()->allMessages()
        ->where(function ($query) use ($request) {
            $query->where('friend_id', $request->id)
                  ->orWhere('user_id', $request->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();
        return response()->json($messages);
    }
}
