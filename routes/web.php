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
    // GET /users - Display list of users
    $router->get('/users', 'UserController@showAll');

    // GET /users/:id - Display specific user
    $router->get('/users/{id}', 'UserController@showOne');

    // POST /users/:id - Create user
    $router->post('/users', 'UserController@create');

    // PUT /users/:id - Edit user
    $router->put('/users/{id}', 'UserController@update');

    // DELETE /users/:id - Delete user
    $router->delete('/users/{id}', 'UserController@delete');
});