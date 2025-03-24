<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\UserInfo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $userData = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'login' => 'required|string|unique:users',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt(['login' => $userData['login'], 'password' => $userData['password']])) 
        {
            $userInfo = UserInfo::create([
                'name' => $userData['name'],
                'surname' => $userData['surname'],
                'category' => null,
                'avatar' => null,

            ]);
            if (!$userInfo) {
                return response()->json(['error' => 'UserInfo not created'], 500);
            }
            $user = User::create([
                'userInfo_id' => $userInfo->id,
                'login' => $userData['login'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role_id' => 2
            ]);
            Auth::attempt(['login' => $userData['login'], 'password' => $userData['password']]);
            return response()->json($user->load('userInfo'));
        }
    }
    public function login(Request $request)
    {
        $userData = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
        if (Auth::attempt(['login' => $userData['login'], 'password' => $userData['password']])) 
        {
            $user = Auth::user()->load('userInfo');
            return response()->json($user);
        }
        return response()->json(['message' => 'Unauthorized'], 401);

    }
    public function logout(Request $request)
    {
        if (Auth::check()) 
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return response('Вы успешно вышли из аккаунта');
    }
    public function isAuth()
    {
        $auth = Auth::check();
        return response()->json($auth);
    }
    public function getUser(Request $request, $id)
    {
        // $user = $request->user()->load('userInfo');

        $user = User::find($id)->load('userInfo');
        $chatIds = $user->chats()->pluck('id');

        $allFriend = $user->allFriends()->get()->map(function ($friend) use ($chatIds, $user) {
            $chat = Chat::where('user_id', $friend->id)
            ->where('friend_id', $user->id)
            ->orWhere('friend_id', $friend->id)
            ->where('user_id', $user->id)
            ->whereIn('id', $chatIds)
            ->first();

            if (!$chat) {
                $chat = Chat::create([
                    'name' => $user->userInfo->name.' и '.$friend->userInfo->name,
                    'user_id' => $user->id,
                    'friend_id' => $friend->id,
                ]);
            }

            return [
                'id' => $friend->id,
                'name' => $friend->userInfo->name,
                'surname' => $friend->userInfo->surname,
                'avatar' => $friend->userInfo->avatar,
                'quote' => $friend->userInfo->quote,
                'chat_id' => $chat->id, 
            ];

        });
    
        $data = [
            'id' => $user->id,
            'friends' => $allFriend,
            'info' => $user->userInfo,
            'role_id' => $user->role_id,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
        return response()->json($data);
    }
    public function isLoginAvailability(Request $request)
    {
        if(User::where('login',$request->login)->exists())
        {
            return response()->json(false);
        }
        else 
        {
            return response()->json(true);
        }
    }
    public function changeLogin(Request $request)
    {
        $user = $request->user();
        if(!User::where('login',$request->login)->get())
        {
            User::where('id', $user->id)->update(['login' => $request->login]);
            return response()->json(['message' => 'Логин успешно изменен.'], 200);
        }
        return response()->json(['message' => 'Логин занят.'], 403);
    }
    public function changePassword(Request $request)
    {
        $user = $request->user();
        if (Hash::check($request['password_old'], $user->password)) 
        {
            $hashedPassword = Hash::make($request->password_new);
            User::where('id', $user->id)->update(['password' => $hashedPassword]);
            return response()->json(['message' => 'Пароль успешно изменен.'], 200);
        }
        return response()->json(['message' => 'Старый пароль невереный.'], 403);
    }
    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return response()->json(['message'=> 'Аккаунт был успешно удален']);
    }
}
