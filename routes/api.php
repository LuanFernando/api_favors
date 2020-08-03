<?php

use Illuminate\Http\Request;

//Route::apiResource('usuarios', 'api\UsuarioController');

Route::apiResource('categorias', 'api\CategoriaController');

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'Api\AuthController@login');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('me', 'Api\AuthController@me');


});
