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

$router->get('/ping', function () use ($router) {
    return [
        'time' => date('Y-m-d H:i:s'),
        'message' => 'pong'
    ];
});


// POST /auth/login - Login with email and password
$router->post('/auth/login', 'AuthController@login');

// POST /auth/logout - Revoke refresh token
$router->post('/auth/logout', 'AuthController@logout');

// POST /auth/refresh - Refreshes access_token
$router->post('/auth/refresh', 'AuthController@refresh');


$router->group(['middleware' => 'auth'], function () use ($router) {
    // GET /user - View Account
    $router->get('/user', 'UserController@view');

    // PUT /user - Update Account
    $router->put('/user', 'UserController@update');

    // DELETE /user - Delete Account
    $router->delete('/user', 'UserController@delete');
});