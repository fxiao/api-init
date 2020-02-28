<?php

namespace App\Controllers;

use App\Models\Tag;
use Fxiao\LumenTools\BaseTransformer;
use Illuminate\Http\Request;
use Fxiao\LumenTools\Controller;
use Fxiao\LumenTools\ControllerHelper;

class TagController extends Controller
{
    use ControllerHelper;

    public function __construct()
    {
        $this->route_prefix = 'tags';
        $this->model = new Tag();
        $this->transformer = new BaseTransformer();
    }

    public function storeValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'name' => 'required|string',
            'order' => 'required|integer',
        ]);
    }

    public function updateValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'name' => 'string',
            'order' => 'integer',
        ]);
    }

    public function fieldOnly(Request $request)
    {
        return $request->only(
            'name',
            'order'
        );
    }

/*
{
    "name":"名称",
    "order":""
}
*/
}
