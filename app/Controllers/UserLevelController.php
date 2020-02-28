<?php

namespace App\Controllers;

use App\Models\UserLevel;
use App\Transformers\UserLevelTransformer;
use Illuminate\Http\Request;
use Fxiao\LumenTools\Controller;
use Fxiao\LumenTools\ControllerHelper;

class UserLevelController extends Controller
{
    use ControllerHelper;

    public function __construct()
    {
        $this->route_prefix = 'user_levels';
        $this->model = new UserLevel();
        $this->transformer = new UserLevelTransformer();
    }

    public function storeValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'name' => 'required|string',
            'mark' => 'string',
            'order' => 'integer',
            'remark' => 'string',
        ]);
    }

    public function updateValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'name' => 'string',
            'mark' => 'string',
            'order' => 'integer',
            'remark' => 'string',
        ]);
    }

    public function fieldOnly(Request $request)
    {
        return $request->only(
            'name',
            'mark',
            'order',
            'remark'
        );
    }

/*
{
    "name":"名称",
    "mark":"唯一标识",
    "order":"排序",
    "remark":"备注"
}
*/
}
