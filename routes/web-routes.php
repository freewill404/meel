<?php

Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);

Route::view('/help', 'help.index')->name('help');


Route::get('login',   ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
Route::post('login',  ['uses' => 'Auth\LoginController@login']);

Route::get('register',        ['uses' => 'Auth\RegisterController@showRegistrationForm', 'as' => 'register']);
Route::post('register',       ['uses' => 'Auth\RegisterController@register',             'as' => 'register.post']);
Route::get('account-created', ['uses' => 'Auth\RegisterController@registered',           'as' => 'register.done']);
Route::get('confirm-account', ['uses' => 'Auth\RegisterController@confirm',              'as' => 'register.confirm']);

Route::get('password/reset',         ['uses' => 'Auth\ForgotPasswordController@showLinkRequestForm', 'as' => 'password.request']);
Route::post('password/email',        ['uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail',  'as' => 'password.email']);
Route::get('password/requested',     ['uses' => 'Auth\ForgotPasswordController@requestedPassword',   'as' => 'password.requested']);
Route::get('password/reset/{token}', ['uses' => 'Auth\ResetPasswordController@showResetForm',        'as' => 'password.reset']);
Route::post('password/reset',        ['uses' => 'Auth\ResetPasswordController@reset']);

Route::post('logout', ['uses' => 'Auth\LoginController@logout', 'as' => 'logout']);
