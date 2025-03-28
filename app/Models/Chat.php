<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'friend_id',
    ];
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
