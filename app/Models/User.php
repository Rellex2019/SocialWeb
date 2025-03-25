<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

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
        'userInfo_id',
        'role_id',
        'setup_complete'
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
    public function allFriends()
    {
        $friendsQuery = $this->friends()
            ->select('users.id as id', 'users.userInfo_id') // Убедитесь, что здесь указаны те же столбцы
            ->getQuery();
        $friendOfQuery = $this->friendOf()
            ->select('users.id as id', 'users.userInfo_id') // Здесь также должны быть те же столбцы
            ->getQuery();
        return $friendsQuery->union($friendOfQuery);
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
    public function allFriendRequests()
    {
        $requestToFriend = $this->friendRequest()
            ->select('applications_friends.friend_id as id', 'users.userInfo_id')
            ->getQuery();

        $requestFromFriend = $this->friendRequestOf()
            ->select('applications_friends.user_id as id', 'users.userInfo_id')
            ->getQuery();

        return $requestToFriend->union($requestFromFriend);
    }



    public function categories()
    {
        return $this->belongsToMany(Category::class, 'user_categories');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id',);
    }
    public function chats()
    {
        return Chat::where(function ($query) {
            $query->where('user_id', $this->id)
                ->orWhere('friend_id', $this->id);
        });
    }
    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }
}
