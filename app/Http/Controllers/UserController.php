<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function changeUserInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'quote' => 'nullable|string|max:255',
            'imageUpload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20048',
        ]);

        $user = $request->user();
        $userInfo = $user->userInfo;
        
        if ($userInfo) {
            $newInfo = [
                'name' => $request->name,
                'surname' => $request->surname,
                'quote' => $request->quote,
            ];

            $avatarPath = null;

            if ($request->hasFile('imageUpload')) {
                if ($userInfo->avatar) {
                    Storage::disk('public')->delete($userInfo->avatar);
                }
                
                $avatarPath = $request->file('imageUpload')->store('avatars', 'public');
                $newInfo['avatar'] = $avatarPath;
            }

            $userInfo->update($newInfo);

            return response()->json([
                'avatarPath' => $avatarPath ? $avatarPath : null,
                'userInfo' => $userInfo
            ]);
        }

        return response()->json(['error' => 'Пользователь не найден'], 404);
    }
}
