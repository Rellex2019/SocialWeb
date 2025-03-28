<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'userInfo';
    protected $fillable = [
        'name',
        'surname',
        'category',
        'avatar',
        'quote'
    ];
}
