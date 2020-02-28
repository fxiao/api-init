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
    // Auth
    // login
    $api->post('authorizations', [
        'as' => 'authorizations.store',
        'uses' => 'AuthController@store',
    ]);

    $api->put('authorizations/current', [
        'as' => 'authorizations.update',
        'uses' => 'AuthController@update',
    ]);

    $api->post('login', [
        'as' => 'authorizations.loginAndRegister',
        'uses' => 'AuthController@loginAndRegister',
    ]);

    $api->post('reg', [
        'as' => 'authorizations.reg',
        'uses' => 'AuthController@reg',
    ]);

    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {

        $api->delete('authorizations/current', [
            'as' => 'authorizations.destroy',
            'uses' => 'AuthController@destroy',
        ]);

    });
});
