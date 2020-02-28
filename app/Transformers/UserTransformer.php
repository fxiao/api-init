<?php

namespace App\Transformers;

use League\Fractal\ParamBag;
use Fxiao\LumenTools\BaseTransformer;

class UserTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'parent',
        'userLevel',
    ];


    public function includeParent($model, ParamBag $params = null)
    {
        if (!$model->parent) {
            return $this->null();
        }

        return $this->item($model->parent, new BaseTransformer($params));
    }

    public function includeUserLevel($model, ParamBag $params = null)
    {
        if (!$model->userLevel) {
            return $this->null();
        }

        return $this->item($model->userLevel, new BaseTransformer($params));
    }
}
