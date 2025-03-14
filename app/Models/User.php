<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'login',
        'email',
        'password',
        'userInfo_id'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'userInfo_id');
    }
    //Друзья
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id');
    }

    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id');
    }
    //Заявки
    public function friendRequest()
    {
        return $this->belongsToMany(User::class, 'applications_friends', 'user_id', 'friend_id');
    }

    public function friendRequestOf()
    {
        return $this->belongsToMany(User::class, 'applications_friends', 'friend_id', 'user_id');
    }

    public function allFriends()
    {
        return $this->friends()->union($this->friendOf());
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', );
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'friend_id');
    }
    
    public function allMessages()
    {
        return $this->sentMessages()->union($this->receivedMessages());
    }
}
