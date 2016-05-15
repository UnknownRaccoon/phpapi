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

Route::resource('albums', 'AlbumController', [
    'only' => [
        'index', 'store', 'show', 'update', 'destroy'
    ], 'parameters' => [
        'albums' => 'album',
    ],
]);

Route::resource('/albums/{album}/photos', 'PhotoController', [
    'only' => [
        'index', 'store', 'show', 'destroy',
    ], 'parameters' => [
        'photos' => 'photo',
    ],
]);

Route::resource('users', 'UserController', [
    'only' => [
        'index', 'store', 'show', 'update', 'destroy',
    ], 'parameters' => [
        'users' => 'user',
    ],
]);

Route::resource('/albums/{album}/permissions/', 'PermissionController', [
    'only' => [
        'index', 'store',
    ],
]);
Route::post('/login', 'Auth\AuthController@authenticate');
Route::post('/reset', 'Auth\PasswordController@reset');
Route::post('/reset/{token}', 'Auth\PasswordController@setNew');
