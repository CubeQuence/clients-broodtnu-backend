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
    return ['documentation_url' => 'https://lunchbox.gitbook.io/'];
});

$router->get('/ping', ['as' => 'ping', function () use ($router) {
    return ['time' => date('Y-m-d H:i:s'), 'message' => 'pong'];
}]);

$router->group([/*'middleware' => 'auth', */ 'prefix' => 'user', 'as' => 'user'], function () use ($router) {
    $router->get('', 'UsersController@all');            // Get all users
    $router->get('{id}', 'UsersController@get');        // Get specific user
    $router->post('', 'UsersController@add');           // Create user
    $router->put('{id}', 'UsersController@put');        // Update user
    $router->delete('{id}', 'UsersController@remove');  // Delete user
});

// ar wn:resource user "name;string;required;fillable email;string;required;unique;fillable" --add=timestamps