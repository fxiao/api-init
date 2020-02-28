<?php

namespace App\Controllers;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Fxiao\LumenTools\Controller;
use Fxiao\LumenTools\ControllerHelper;

class UserController extends Controller
{
    use ControllerHelper;

    public function __construct()
    {
        $this->route_prefix = 'users';
        $this->model = new User();
        $this->transformer = new UserTransformer();
    }

    public function storeValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'username' => 'required|string',
            'name' => 'required|string',
            'phone' => 'string',
            'password' => 'string',
            'status' => 'required|integer',
            'parent_id' => 'required|integer',
            'user_level_id' => 'integer',
        ]);
    }

    public function updateValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'username' => 'string',
            'name' => 'string',
            'phone' => 'string',
            'password' => 'string',
            'status' => 'integer',
            'parent_id' => 'integer',
            'user_level_id' => 'integer',
        ]);
    }

    public function fieldOnly(Request $request)
    {
        return $request->only(
            'username',
            'name',
            'phone',
            'password',
            'status',
            'parent_id',
            'user_level_id'
        );
    }
}
