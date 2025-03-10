<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function getFriend(Request $request)
    {
        $user = $request->user();
        $friends = $user->friends;
        $friendsInfo = $friends->map(function ($friend) {
            return  $friend->userInfo;
        });

        return response()->json($friendsInfo);
    }
    public function getPeople()
    {
        $allPeoples = User::with('userInfo')->get();
        $peopleInfo = $allPeoples->map(function ($people) {
            return $people;
        });
        return response()->json($peopleInfo);
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
