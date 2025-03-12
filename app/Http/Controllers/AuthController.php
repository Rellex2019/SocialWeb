<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $user = User::create([
                'userInfo_id' => $userInfo->id,
                'login' => $userData['login'],
                'email' => $userData['email'],
                'password' => $userData['password']
            ]);
            Auth::attempt(['login' => $userData['login'], 'password' => $userData['password']]);
            return response([
                $user
            ]);
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
            $user = Auth::user();
            return response([
                $user
            ]);
        }

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
}
