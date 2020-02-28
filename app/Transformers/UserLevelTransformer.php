<?php

namespace App\Transformers;

use League\Fractal\ParamBag;
use Fxiao\LumenTools\BaseTransformer;

class UserLevelTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'user'
    ];


    public function includeUser($model, ParamBag $params = null)
    {
        $limit = $this->withParamLimit($params);
        $relations = $this->withParamOrderFilter($model->user, $params)->take($limit);
        
        return $this->collection($relations, new BaseTransformer($params))
            ->setMeta([
                'limit' => $limit,
                'count' => $relations->count(),
            ]);
    }
}
