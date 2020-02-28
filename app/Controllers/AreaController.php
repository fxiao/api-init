<?php

namespace App\Controllers;

use App\Models\Area;
use App\Transformers\AreaTransformer;
use Illuminate\Http\Request;
use Fxiao\LumenTools\Controller;
use Fxiao\LumenTools\ControllerHelper;

class AreaController extends Controller
{
    use ControllerHelper;

    public function __construct()
    {
        $this->route_prefix = 'areas';
        $this->model = new Area();
        $this->transformer = new AreaTransformer();
    }

    public function storeValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'parent_id' => 'required|string',
            'type' => 'required|string',
            'name' => 'required|string',
            'order' => 'required|integer',
        ]);
    }

    public function updateValidator(Request $request)
    {
        return \Validator::make($this->fieldOnly($request), [
            'parent_id' => 'string',
            'type' => 'string',
            'name' => 'string',
            'order' => 'integer',
        ]);
    }

    public function fieldOnly(Request $request)
    {
        return $request->only(
            'parent_id',
            'type',
            'name',
            'order'
        );
    }

/*
{
    "parent_id":"父级",
    "type":"类型",
    "name":"名称",
    "order":"排序"
}
*/
}
