<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
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
    $posts = $request->user()->posts()->with('photos', 'likes')->get();
    foreach($posts as $post)
    {
      $post->likes->makeHidden(['pivot']);
    }
    return response()->json($posts);
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

  public function getCategories(Request $request)
  {
    $categories = Category::all();
    return response()->json($categories);
  }

  //Лайки
  public function toggleLike(Request $request, $id)
  {
    $userId = $request->user()->id;
    $like = DB::table('likes')->where('post_id', $id)->where('user_id', $userId)->first();
    if($like)
    {
      DB::table('likes')->where('id', $like->id)->delete();
      return response()->json(['message'=> 'Лайк удален']);
    }
    else {
      DB::table('likes')->insert([
        'post_id'=> $id,
        'user_id'=> $userId
      ]);
    }
    return response()->json(['message' => 'Лайк добавлен']);
  }
  //Комментарии 

  public function getComment(Request $request, $id)
  {
    $comments = Comment::where('post_id', $id)->get();
    $commentsWithUser = $comments->map(function ($comment){
      $user = User::find($comment->user_id);
      return [
        'comment'=> $comment,
        'user' => $user->userInfo
      ];
    });
    return response()->json($commentsWithUser);
  }
  public function storeComment(Request $request, $id)
  {
    $user = $request->user();
    $comment = Comment::create([
      'user_id'=> $user->id,
      'post_id'=> $id,
      'body' => $request->body
    ]);
    return response()->json(['message' => 'Комментарий был создан']);
  }

}
