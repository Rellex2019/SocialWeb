<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
  public function storePost(Request $request)
  {
    $user = $request->user();
    $validated = $request->validate([
      'title' => 'required',
      'body' => 'required',
      'category_id' => 'required|exists:categories,id'
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
    $posts = Post::with('photos', 'likes')-> get();
    foreach($posts as $post)
    {
      $post->likes->makeHidden(['pivot']);
    }
    return response()->json($posts);
  }
  public function getCategories(Request $request)
  {
    $categories = Category::all();
    return response()->json($categories);
  }
  public function addLike(Request $request, $postId)
  {
    $userId = $request->user()->id;
    $like = DB::table('likes')->where('post_id', $postId)->where('user_id', $userId)->first();
    if($like)
    {
      DB::table('likes')->where('id', $like->id)->delete();
      return response()->json(['message'=> 'Лайк удален']);
    }
    else {
      DB::table('likes')->insert([
        'post_id'=> $postId,
        'user_id'=> $userId
      ]);
    }
    return response()->json(['message' => 'Лайк добавлен']);
  }
}
