<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function storePost(Request $request)
  {
    $user = $request->user();
    $validated = $request->validate([
      'title' => 'required',
      'body' => 'required',
    ]);
    Post::create([
      ...$validated,
      'user_id' => $user->id
    ]);
    return response()->json('Пост создан');
  }
  public function getMyPosts(Request $request)
  {
    $posts = $request->user()->posts;
    return response()->json($posts);
  }

  public function deleteMyPost(Request $request)
  {
    $user = $request->user();
    $user->posts()->where('id', $request->id)->delete();
    return response()->json('Пост под id удален:' . `$request->id`);
  }
  public function changeMyPost(Request $request)
  {
    $user = $request->user();
    $changedPost = $user->posts()->where('id', $request->id)->update([
      'title' => $request->title,
      'body' => $request->body
    ]);
    return response()->json('Пост под id изменен:' . $changedPost);
  }
  public function getPosts(Request $request)
  {
    $posts = Post::all();
    return response()->json($posts);
    
  }
}
