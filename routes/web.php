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
    return [
        'documentation_url' => 'https://docs.broodt.nu'
    ];
});

$router->get('ping', function () use ($router) {
    return [
        'time' => date('Y-m-d H:i:s'),
        'message' => 'pong'
    ];
});


/**
 * Authentication
 */
$router->post('auth/login', 'AuthController@login');
$router->post('auth/logout', 'AuthController@logout');
$router->post('auth/refresh', 'AuthController@refresh');
$router->post('auth/register', 'AuthController@register');


$router->group(['middleware' => 'auth'], function () use ($router) {
    /**
     * Current User
     */
    $router->get('user', 'UserController@view');
    $router->put('user', 'UserController@update');
    $router->delete('user', 'UserController@delete');
});