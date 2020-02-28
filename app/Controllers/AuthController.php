<?php

namespace App\Controllers;

use Illuminate\Http\Request;
use App\Models\Authorization;
use Fxiao\LumenTools\Controller;
use App\Transformers\AuthorizationTransformer;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' => 'digits:11',
            'password' => 'string',
            'code' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $credentials = $request->only('phone', 'password', 'code');

        // 验证失败返回401
        if (! $token = \Auth::attempt($credentials)) {
            $this->response->errorUnauthorized("手机号或密码错误！");
        }

        $authorization = new Authorization($token);

        return $this->response->item($authorization, new AuthorizationTransformer())
            ->setStatusCode(201);
    }

    public function update()
    {
        $authorization = new Authorization(\Auth::refresh());

        return $this->response->item($authorization, new AuthorizationTransformer());
    }

    public function destroy()
    {
        \Auth::logout();

        return $this->response->noContent();
    }

}
