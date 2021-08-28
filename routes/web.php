<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->get('logout', 'AuthController@logout');
    $router->get('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');
});

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('get', 'UserController@get');
    $router->post('insert', 'UserController@insert');
    $router->post('password', [
        'middleware' => 'auth',
        'uses' => 'UserController@password'
    ]);
});

$router->group(['middleware' => 'auth', 'prefix' => 'fungsi'], function () use ($router) {
    $router->get('get', 'FungsiController@get');
});

$router->group(['middleware' => 'auth', 'prefix' => 'kegiatan'], function () use ($router) {
    $router->get('get', 'KegiatanController@get');
    $router->get('get/join', 'KegiatanController@getJoinFungsi');
    $router->get('get/{fungsi_id}', 'KegiatanController@getByFungsi');
    $router->post('insert', 'KegiatanController@insert');
    $router->post('edit', 'KegiatanController@edit');
    $router->post('delete', 'KegiatanController@delete');
});

$router->group(['middleware' => 'auth', 'prefix' => 'file'], function () use ($router) {
    $router->post('get', 'FileController@get');
    $router->post('insert', 'FileController@insert');
    $router->post('edit', 'FileController@edit');
    $router->post('delete', 'FileController@delete');
});
