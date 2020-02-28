<?php

namespace App\Transformers;

use League\Fractal\ParamBag;
use Fxiao\LumenTools\BaseTransformer;

class AreaTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'parent'
    ];


    public function includeParent($model, ParamBag $params = null)
    {
        if (!$model->parent) {
            return $this->null();
        }

        return $this->item($model->parent, new BaseTransformer($params));
    }
}
