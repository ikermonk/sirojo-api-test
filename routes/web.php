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

/* API */
$router->group(['prefix' => 'api/v1/siroko'], function () use ($router) {
    $router->get('cart/{user_id}', '\Src\App\Cart\Presentation\Controllers\CartController@find');
    $router->post('cart-item', '\Src\App\Cart\Presentation\Controllers\CartController@add_item');
    $router->delete('remove-item', '\Src\App\Cart\Presentation\Controllers\CartController@remove_item');
});
