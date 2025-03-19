<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function getFriend(Request $request)
    {
        $user = $request->user();
        $friends = $user->allFriends()->with('userInfo')->get();
        foreach ($friends as $friend) {
            $friend->chats = $friend->chats()
            ->where('user_id', $user->id)
            ->orWhere('friend_id', $user->id)
            ->first();
        }


        // $friendsInfo = $friends->map(function ($friend) {
        //   return  [
        //     ...$friend,
        //     $friend->chat()
        //   ];
        // });

        return response()->json($friends);
    }
    public function getPeople(Request $request)
    {
        $user = $request->user();
        $friendIds = $user->allFriends()->pluck('id')->toArray();
        $requestToFriend = $user->allFriendRequests()->pluck('id')->toArray();
        $allPeoples = User::with('userInfo')
            ->whereNotIn('id', $friendIds)
            ->whereNotIn('id', $requestToFriend)
            ->where('id', '!=', $user->id)
            ->get();
        return response()->json($allPeoples);
    }
    public function storeFriendRequest(Request $request)
    {
        $user = $request->user();
        $friendId = $request->id;

        $application = DB::table('applications_friends')
            ->where(function ($query) use ($user, $friendId) {
                $query->where('friend_id', $friendId)
                    ->where('user_id', $user->id);
            })
            ->orWhere(function ($query) use ($user, $friendId) {
                $query->where('friend_id', $user->id)
                    ->where('user_id', $friendId);
            })->first();
        if ($application && $application->friend_id == $user->id) {
            DB::table('friendships')->insert([
                'user_id' => $user->id,
                'friend_id' => $friendId,
            ]);
            DB::table('applications_friends')
                ->where('id', $application->id)
                ->delete();
            return response([
                'Друг добавлен',
                'id' => $request->id
            ]);
        } else {
            if ($application) {
                return response([
                    'Заявка уже была отправлена, дождитесь ответа',
                ]);
            } else {
                $user->friendRequest()->attach($friendId);
                return response([
                    'Заявка отправлена',
                    'id' => $friendId
                ]);
            }
        }
    }
    public function deleteFriendRequest(Request $request)
    {
        $user = $request->user();
        $friendId = $request->id;
        $friendRequest = DB::table('applications_friends')
            ->where('user_id', $user->id)
            ->where('friend_id', $friendId)
            ->orWhere('user_id', $friendId)
            ->where('friend_id', $user->id)
            ->first();

        if ($friendRequest && ($friendRequest->user_id === $user->id || $friendRequest->friend_id === $user->id)) {
            DB::table('applications_friends')->where('id', $friendRequest->id)->delete();
            return response()->json(['message' => 'Запрос на дружбу удален.']);
        }
        return response()->json(['message' => 'Запрос на дружбу не найден или у вас нет прав для его удаления.'], 404);
    }
    public function deleteFriend(Request $request, $id)
    {
        $user = $request->user();
        $friendId = $id;
        $friendRequest = DB::table('friendships')
            ->where('user_id', $user->id)
            ->where('friend_id', $friendId)
            ->orWhere('user_id', $friendId)
            ->where('friend_id', $user->id)
            ->first();
        if ($friendRequest && ($friendRequest->user_id === $user->id || $friendRequest->friend_id === $user->id)) {
            DB::table('friendships')->where('id', $friendRequest->id)->delete();
            return response()->json(['message' => 'Пользователь был удален из друзей.']);
        }
        return response()->json(['message' => 'Друг не найден или у вас нет прав для его удаления.'], 404);
    }
    public function getFriendRequest(Request $request)
    {
        $user = $request->user();
        $requestToFriend = $user->friendRequestOf()->with('userInfo')->get();
        return response()->json($requestToFriend);
    }
    public function getUserRequest(Request $request)
    {
        $user = $request->user();
        $requestToFriend = $user->friendRequest()->with('userInfo')->get();
        return response()->json($requestToFriend);
    }
}
