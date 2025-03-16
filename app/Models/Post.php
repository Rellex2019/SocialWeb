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
        return $this->belongsToMany(Post::class,'likes','post_id','user_id');
    }
}
