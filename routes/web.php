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
        'documentation_url' => 'https://lunchbox.gitbook.io/'
    ];
});

$router->get('/ping', function () use ($router) {
    return [
        'time' => date('Y-m-d H:i:s'),
        'message' => 'pong'
    ];
});

$router->group([/*'middleware' => 'auth'*/], function () use ($router) {
    // GET /users - display list of users
    $router->get('/users', 'UserController@showAll');

    // GET /users/:id - display specific user
    $router->get('/users/{id}', 'UserController@showOne');

    // POST /users/:id - add user
    $router->post('/users', 'UserController@create');

    // PUT /users/:id - edit user
    $router->put('/users/{id}', 'UserController@update');

    // DELETE /users/:id - delete user
    $router->delete('/users/{id}', 'UserController@delete');

    // GET /products - Get all products
});

/*
 * Routes for:
 * /auth
 *      /login      - Login with email and password. [Return a JWT, Refresh token]
 *          /google - Login with Google. [Return a JWT, Refresh token]
 *
 *      /logout     - Authenticate with JWT. [Revoke refresh_token] (How do you know which refresh token to revoke? Maybe place refresh_token id in payload)
 *
 *      /refresh    - Authenticate with refresh_token. [Refresh JWT]
 *
 * */