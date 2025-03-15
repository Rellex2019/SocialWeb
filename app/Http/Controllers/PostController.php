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
    $post = Post::create([
      ...$validated,
      'user_id' => $user->id,
      'published_at' => now(),
      'status' => 'published'
    ]);
    if ($request->hasFile('photos')) {
      foreach ($request->file('photos') as $file) {
        $path = $file->store('photos', 'public'); 
        $post->photos()->create(['path' => $path]);
      }
    }
    return response()->json('Пост создан');
  }
  public function getMyPosts(Request $request)
  {
    $posts = $request->user()->posts()->with('photos')->get();
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
