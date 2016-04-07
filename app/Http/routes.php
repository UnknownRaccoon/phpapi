<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('albums', 'AlbumController', ['only' => [
    'index', 'store', 'show', 'update', 'destroy'
]]);

Route::resource('users', 'Auth\AuthController', ['only' => [
    'index', 'store', 'show', 'update', 'destroy'
]]);

Route::post('/login', 'Auth\AuthController@authenticate');
