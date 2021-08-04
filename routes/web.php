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

$router->GET('/', function () {
    return view('index');
});
$router->GET('/addNewCustomerPage', function () {
    return view('addNewCustomerPage');
});
$router->GET('/updateCustomerPage/{id}', function () {
    return view('updateCustomerPage');
});


$router->group(['prefix' => '/customer'], function () use ($router) {
    $router->GET('/selectAll', 'CustomerController\CustomerCrudController@selectAll');
    $router->POST('/addNewCustomer', 'CustomerController\CustomerCrudController@store');
    $router->POST('/update/{id}', 'CustomerController\CustomerCrudController@update');
    $router->GET('/select/{id}', 'CustomerController\CustomerCrudController@show');
    $router->GET('/delete/{id}', 'CustomerController\CustomerCrudController@destroy');
    $router->GET('/deleteAll', 'CustomerController\CustomerCrudController@destroyAll');
    $router->GET('/re/delete/{id}', 'CustomerController\CustomerCrudController@reDestroy');
    $router->GET('/re/deleteAll', 'CustomerController\CustomerCrudController@reDestroyAll');
});
