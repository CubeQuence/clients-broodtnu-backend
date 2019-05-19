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
 * Testing
 */
$router->get('mail/request_reset', function () {
    $user = App\Models\User::find(1);
	return new App\Mail\RequestResetPassword($user);
});

$router->get('mail/verify', function () {
	$user = App\Models\User::find(1);
	return new App\Mail\RegisterConfirmation($user);
});


/**
 * General
 */
$router->get('/', 'GeneralController@root');
$router->get('ping', 'GeneralController@ping');
$router->get('meta', 'GeneralController@meta');


/**
 * Authentication
 */
$router->post('auth/login', 'AuthController@login');
$router->post('auth/logout', 'AuthController@logout');
$router->post('auth/register', 'AuthController@register');
$router->post('auth/refresh', 'AuthController@refresh');

$router->post('auth/reset_request', 'AuthController@requestResetPassword');
$router->post('auth/reset', 'AuthController@resetPassword');
$router->post('auth/verify', 'AuthController@verifyEmail');


$router->group(['middleware' => 'auth'], function () use ($router) {
    /**
     * Current User
     */
    $router->get('user', 'UserController@index');
    $router->put('user', 'UserController@update');
    $router->delete('user', 'UserController@delete');
});