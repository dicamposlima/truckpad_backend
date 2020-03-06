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

$router->get('/', function () use ($router) {
    return redirect()->route('drivers');
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->get('/', function () use ($router) {
        return redirect()->route('drivers');
    });

    $router->get('/drivers', [
        'as' => 'drivers', 'uses' => 'DriverController@index'
    ]);

    $router->get('/drivers/hasTruckloadList', 'DriverController@hasTruckloadList');

    $router->get('/drivers/hasVehiclesQtd', 'DriverController@hasVehiclesQtd');

    $router->post('/drivers', 'DriverController@store');

    $router->put('/drivers/{id}', 'DriverController@update');

    $router->put('/drivers/{id}/active', 'DriverController@updateStatus');

    $router->get('/tracks', 'TrackController@index');

    $router->post('/tracks', 'TrackController@store');

    $router->get('/tracks/tracking', 'TrackController@tracking');

    $router->get('/tracks/trackingByTypes', 'TrackController@trackingByTypes');
});
