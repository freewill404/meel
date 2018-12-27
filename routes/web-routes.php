<?php

Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);

Route::view('/help', 'help.index')->name('help');
Route::view('/about', 'help.about')->name('about');
Route::view('/about/schedules', 'help.schedules-intro')->name('schedules-intro');
Route::view('/about/feeds', 'help.feeds-intro')->name('feeds-intro');

Route::get('login',   ['uses' => 'LoginController@showLoginForm', 'as' => 'login'])->middleware('guest');
Route::post('login',  ['uses' => 'LoginController@login',         'as' => 'login.post'])->middleware('guest');
Route::post('logout', ['uses' => 'LoginController@logout',        'as' => 'logout']);

Route::get('register',        ['uses' => 'RegisterController@showRegistrationForm', 'as' => 'register'])->middleware('guest');
Route::post('register',       ['uses' => 'RegisterController@register',             'as' => 'register.post'])->middleware('guest');
Route::get('account-created', ['uses' => 'RegisterController@registered',           'as' => 'register.done'])->middleware('guest');
Route::get('confirm-account', ['uses' => 'RegisterController@confirm',              'as' => 'register.confirm'])->middleware('guest');

Route::get('password/reset',         ['uses' => 'ForgotPasswordController@showLinkRequestForm', 'as' => 'password.request'])->middleware('guest');
Route::post('password/email',        ['uses' => 'ForgotPasswordController@sendResetLinkEmail',  'as' => 'password.email'])->middleware('guest');
Route::get('password/requested',     ['uses' => 'ForgotPasswordController@requestedPassword',   'as' => 'password.requested'])->middleware('guest');

Route::get('password/reset/{token}', ['uses' => 'ResetPasswordController@showResetForm', 'as' => 'password.reset'])->middleware('guest');
Route::post('password/reset',        ['uses' => 'ResetPasswordController@reset'])->middleware('guest');
