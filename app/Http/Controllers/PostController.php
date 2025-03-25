<?php

namespace App\Http\Controllers;

use App\Events\CommentSend;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
  public function getUserPosts(Request $request, $id)
  {
    $user = User::find($id);
    $posts = $user->posts()->with('photos', 'likes', 'user.userInfo', 'category')
    ->orderBy('created_at', 'desc')
    ->get();
    foreach ($posts as $post) {
      $post->likes->makeHidden(['pivot']);
      $post->makeHidden(['user_id', 'category_id']);
    }
    return response()->json($posts);
  }
  public function getPosts(Request $request)
  {
    $posts = Post::with(['photos', 'likes', 'user.userInfo', 'category'])
    ->orderBy('created_at', 'desc')
    ->get();
    foreach ($posts as $post) {
      $post->likes->makeHidden(['pivot']);
      $post->makeHidden(['user_id', 'category_id']);
    }
    return response()->json($posts);
  }
  public function deleteMyPost(Request $request)
  {
    $user = $request->user();
    $user->posts()->where('id', $request->id)->delete();
    return response()->json('Пост под id удален:' . `$request->id`);
  }
  public function changeMyPost(Request $request, $id)
  {
    $user = $request->user();
    $post = $user->posts()->where('id', $id)->first();

    if (!$post) {
      return response()->json(['error' => 'Пост не найден'], 404);
    }


    $post->update([
      'title' => $request->title,
      'body' => $request->body,
      'category_id' => $request->category_id
    ]);

    if ($request->hasFile('photos')) {
      foreach ($post->photos as $photo) {
        Storage::disk('public')->delete($photo->path);
        $photo->delete();
      }
      foreach ($request->file('photos') as $file) {
        $path = $file->store('photos', 'public');
        $post->photos()->create(['path' => $path]);
      }
    }

    return response()->json(['message' => 'Пост изменен']);
  }


  public function changeCategories(Request $request)
  {
    $user = $request->user();
    $categoryIds = $request->input('ids', []);
    $user->categories()->sync($categoryIds);
    return response()->json($user->categories);
  }

  public function getUserCategories(Request $request)
  {
    $user = $request->user();
    $categories = $user->categories()->get();
    return response()->json($categories);
  }

  //Лайки
  public function toggleLike(Request $request, $id)
  {
    $userId = $request->user()->id;
    $like = DB::table('likes')->where('post_id', $id)->where('user_id', $userId)->first();
    if ($like) {
      DB::table('likes')->where('id', $like->id)->delete();
      return response()->json(['message' => 'Лайк удален']);
    } else {
      DB::table('likes')->insert([
        'post_id' => $id,
        'user_id' => $userId
      ]);
    }
    return response()->json(['message' => 'Лайк добавлен']);
  }
  //Комментарии 

  public function getComment(Request $request, $id)
  {
    $comments = Comment::where('post_id', $id)
    ->orderBy('created_at', 'desc')
    ->get();
    $commentsWithUser = $comments->map(function ($comment) {
      $user = User::find($comment->user_id);
      return [
        'comment' => $comment,
        'user' => $user->userInfo
      ];
    });
    return response()->json($commentsWithUser);
  }
  public function storeComment(Request $request, $id)
  {
    $user = $request->user();
    $comment = Comment::create([
      'user_id' => $user->id,
      'post_id' => $id,
      'body' => $request->body
    ]);
    $ownerComment = User::find($comment->user_id);
    broadcast(new CommentSend($comment));
    return response()->json([
      'message' => 'Комментарий был создан',
      'comment' => $comment,
      'user'=> $ownerComment->userinfo
    ]);
  }
}
