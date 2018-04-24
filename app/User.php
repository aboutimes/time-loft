<?php

namespace App;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'description',
        'last_login_ip',
        'avatar_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';

    public function findForPassport($username)
    {
        //兼容名字/邮箱/电话登陆，需要前端区分字段
        //扩展自vendor/laravel/passport/src/Bridge/UserRepository.php
        return $this->where('email', $username)
            ->orWhere('name', $username)
            ->orWhere('mobile', $username)
            ->first();
    }
}