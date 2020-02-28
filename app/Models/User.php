<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract, JWTSubject
{
    use SoftDeletes, Authenticatable;

    protected $hidden = ['password', 'deleted_at'];
    protected $fillable = [
        'username',
        'name',
        'phone',
        'password',
        'status',
        'avatar',
        'parent_id',
        'user_level_id',
    ];


    // jwt 需要实现的方法
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // jwt 需要实现的方法, 一些自定义的参数
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function parent()
    {
        return $this->belongsTo(User::class);
    }

    public function userLevel()
    {
    	return $this->belongsTo(UserLevel::class);
    }
}
