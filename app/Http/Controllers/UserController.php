<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $userInfo = $user->userInfo();
        if ($userInfo) {
            $newInfo =[
                'name' => $request->name,
                'surname' => $request->surname,
                'quote' => $request->quote,
            ];
            if ($request->hasFile('imageUpload')) {
                $imagePath = $request->file('imageUpload')->store('avatars', 'public');
                // $userInfo->avatar = $imagePath;
                $newInfo['avatar'] = $imagePath;
            }
            $userInfo->update($newInfo);
        }
        return response()->json($userInfo);

    }
}
