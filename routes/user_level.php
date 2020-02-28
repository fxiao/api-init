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
    'limit' => app('config')['app']['rate_limit'], 'expires' => 1,
], function ($api) {

    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {

        $api->post('user-level', [
            'as' => 'user_levels.store',
            'uses' => 'UserLevelController@store',
        ]);

        $api->get('user-level', [
            'as' => 'user_levels.index',
            'uses' => 'UserLevelController@index',
        ]);

        $api->get('user-level/{id}', [
            'as' => 'user_levels.show',
            'uses' => 'UserLevelController@show',
        ]);

        $api->put('user-level/{id}', [
            'as' => 'user_levels.update',
            'uses' => 'UserLevelController@update',
        ]);

        $api->delete('user-level/{id}', [
            'as' => 'user_levels.destroy',
            'uses' => 'UserLevelController@destroy',
        ]);
    });
});
