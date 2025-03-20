<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'category_id',
        'status',
        'published_at',
    ];
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes', 'post_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
