<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 获取token
    public function getAccessTokenAttribute()
    {
        $token = $this->token();
        return $token->id;
    }

    // 自定义授权用户名（默认为登录账号）
    public function findForPassport($username)
    {
        return User::where('name', $username)->first();
    }
}
