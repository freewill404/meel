<?php

Route::get('/',   ['uses' => 'HomeController@index',   'as' => 'home']);
Route::post('/',  ['uses' => 'HomeController@post',    'as' => 'home.post']);
Route::get('/ok', ['uses' => 'HomeController@success', 'as' => 'home.success']);

Route::get('/account', ['uses' => 'AccountController@index', 'as' => 'account']);

Route::get('login',   ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
Route::post('login',  ['uses' => 'Auth\LoginController@login']);
Route::post('logout', ['uses' => 'Auth\LoginController@logout',        'as' => 'logout']);

Route::get('register',  ['uses' => 'Auth\RegisterController@showRegistrationForm', 'as' => 'register']);
Route::post('register', ['uses' => 'Auth\RegisterController@register',             'as' => 'register.post']);

Route::get('password/reset',         ['uses' => 'Auth\ForgotPasswordController@showLinkRequestForm', 'as' => 'password.request']);
Route::post('password/email',        ['uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail',  'as' => 'password.email']);
Route::get('password/reset/{token}', ['uses' => 'Auth\ResetPasswordController@showResetForm',        'as' => 'password.reset']);
Route::post('password/reset',        ['uses' => 'Auth\ResetPasswordController@reset']);
