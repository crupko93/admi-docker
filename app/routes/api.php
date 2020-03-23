<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['guest:api']], function() {
    Route::post('login', 'Auth\LoginController@login');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('register', 'Auth\RegisterController@register');
});

Route::post('login/refresh', 'Auth\LoginController@refresh');

Route::group(['middleware' => ['jwt']], function() {
    Route::post('logout', 'Auth\LoginController@logout');

    AdvancedRoute::controllers([
        'roles'       => 'API\RoleAPIController',
        'profile'     => 'API\ProfileAPIController',
        'permissions' => 'API\PermissionAPIController',
        'users'       => 'API\UserAPIController'
    ]);
});

