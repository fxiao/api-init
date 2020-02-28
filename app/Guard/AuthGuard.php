<?php

namespace App\Guard;

use App\Models\User;
use App\Service\Pwt;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Support\Arr;

class AuthGuard extends JWTGuard
{

    public function attempt(array $credentials = [], $login = true)
    {
        $user = false;

        //账号密码登陆
        if (empty($user) && array_key_exists('password', $credentials) && array_key_exists('phone', $credentials)) {
            $u = User::where('phone', $credentials['phone'])->first();
            if(parent::attempt(Arr::only($credentials, ['phone', 'password']), $login)) {
                $user = $u;
            }

        }

        //手机号验证码登陆
        if (array_key_exists('code', $credentials) && array_key_exists('phone', $credentials)) {
            (new Pwt)->verify(
                app('request')->headers->get('pwtp', null),
                $credentials['phone'],
                $credentials['code']
            );
            $user = User::where('phone', $credentials['phone'])->first();

        }

        if ($user) {
            return $login ? $this->login($user) : true;
        }

        return false;
    }

}
