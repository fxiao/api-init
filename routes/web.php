<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [], function ($api) {
    $api->get('/', function () {
        return '欢迎您！';
    });

    $api->get('/404', function () {
        return '当前接口不存在！';
    });
});
