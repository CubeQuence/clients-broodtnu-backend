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

/**
 * General
 */
$router->get('/', 'GeneralController@index');
$router->get('meta', 'GeneralController@meta');


/**
 * Auth
 */
$router->post('auth/register', 'AuthController@register');
$router->post('auth/verify', 'AuthController@verifyEmail');
$router->post('auth/login', 'AuthController@login');
$router->post('auth/refresh', 'AuthController@refresh');
$router->post('auth/reset/request', 'AuthController@requestResetPassword');
$router->post('auth/reset', 'AuthController@resetPassword');


$router->group(['middleware' => 'auth'], function () use ($router) {
    /**
     * Auth
     */
    $router->post('auth/logout', 'AuthController@logout');

    /**
     * User
     */
    $router->get('user', 'UserController@index');
    $router->put('user', 'UserController@update');
    $router->delete('user', 'UserController@delete');
    $router->get('user/sessions', 'UserController@showSessions');
    $router->delete('user/sessions/{uuid:[0-9a-z-]+}', 'UserController@revokeSession');

    /**
     * Products
     */
    $router->get('products', 'ProductsController@index');
    $router->get('products/{id:[0-9,]+}', 'ProductsController@show');

    $router->post('products', 'ProductsController@create');
    $router->put('products/{id:[0-9]+}', 'ProductsController@update');
    $router->delete('products/{id:[0-9]+}', 'ProductsController@delete');

    /**
     * Tags
     */
    $router->get('tags', 'TagsController@index');
    $router->get('tags/{id:[0-9,]+}', 'TagsController@show');
    $router->get('tags/{id:[0-9,]+}/products', 'TagsController@showProducts');

    $router->post('tags', 'TagsController@create');
    $router->put('tags/{id:[0-9]+}', 'TagsController@update');
    $router->delete('tags/{id:[0-9]+}', 'TagsController@delete');
});