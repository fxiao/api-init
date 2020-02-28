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

        $api->post('areas', [
            'as' => 'areas.store',
            'uses' => 'AreaController@store',
        ]);

        $api->get('areas', [
            'as' => 'areas.index',
            'uses' => 'AreaController@index',
        ]);

        $api->get('areas/{id}', [
            'as' => 'areas.show',
            'uses' => 'AreaController@show',
        ]);

        $api->put('areas/{id}', [
            'as' => 'areas.update',
            'uses' => 'AreaController@update',
        ]);

        $api->delete('areas/{id}', [
            'as' => 'areas.destroy',
            'uses' => 'AreaController@destroy',
        ]);
    });
});
