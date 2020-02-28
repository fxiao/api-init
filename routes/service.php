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
// v1 version API
// add in header    Accept:application/vnd.lumen.v1+json
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Controllers',
    'middleware' => [
        'api.throttle',
    ],
    // each route have a limit of 20 of 1 minutes
    // 'limit' => app('config')['app']['rate_limit'], 'expires' => 1,
], function ($api) {

    // uri 受权加密码
    $api->post('verify-code/uri', [
        'as' => 'verify_code.uri',
        'uses' => 'ServiceController@uri',
    ]);

    // phone 受权加密码
    $api->post('verify-code/phone', [
        'as' => 'verify_code.phone',
        'uses' => 'ServiceController@phone',
    ]);

    // 发短信验证码
    $api->post('verify-code/sms', [
        'as' => 'verify_code.sms',
        'uses' => 'ServiceController@sms',
    ]);
});
