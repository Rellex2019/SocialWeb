<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'status',
        'published_at',
    ];
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
