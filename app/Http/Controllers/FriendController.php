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
        $friends = $user->friends()->with('userInfo')->get();
        $friendsInfo = $friends->map(function ($friend) {
            return  $friend;
        });

        return response()->json($friendsInfo);
    }
    public function getPeople(Request $request)
    {
        $user = $request->user();
        $friendIds= $user->friends()->pluck('friend_id')->toArray();
        $requestToFriend = $user->friendRequest()->pluck('friend_id')->toArray();
        $allPeoples = User::with('userInfo')
        ->whereNotIn('id', $friendIds) 
        ->whereNotIn('id', $requestToFriend)
        ->get();
        return response()->json($allPeoples);
    }
    public function storeFriendRequest(Request $request)
    {
        $user = $request->user();
    
        $application = DB::table('applications_friends')
        ->where('friend_id', $request->id)
        ->where('user_id', $user->id)
        ->first();
        if($application)
        {
            DB::table('friendships')->insert([
                'user_id'=> $user->id,
                'friend_id'=> $request->id,
            ]);
            DB::table('applications_friends')->where('id', $application->id)->delete();

        }
        else{
            $user->friendRequest()->attach($request->id);
            return response([
                'Заявка отправлена',
                'id'=> $request->id
            ]);
        }
    }
    public function deleteFriendRequest(Request $request)
    {
        $user = $request->user();
        $user->friendRequest()->detach($request->id);
        return response()->json();
    }
    public function deleteFriend(Request $request)
    {
        $user = $request->user();
        $user->friends()->detach($request->id);
        return response()->json();
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
    public function storeFriend(Request $request)
    {
        $user = $request->user();
        $user->friends()->attach($request->id);
        return response([
            'Друг добавлен',
            'id'=> $request->id
        ]);
    }
}
