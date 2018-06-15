<?php

Route::get('/',   ['uses' => 'HomeController@index',   'as' => 'home']);
Route::post('/',  ['uses' => 'HomeController@post',    'as' => 'home.post']);
Route::get('/ok', ['uses' => 'HomeController@success', 'as' => 'home.success']);

Route::get('/more', ['uses' => 'MoreController@more', 'as' => 'more']);

Route::get('/request-format',      ['uses' => 'RequestFormatController@index', 'as' => 'requestFormat']);
Route::post('/request-format',     ['uses' => 'RequestFormatController@post',  'as' => 'requestFormat.post']);
Route::get('/request-format/done', ['uses' => 'RequestFormatController@done',  'as' => 'requestFormat.done']);

Route::get('/feedback',      ['uses' => 'FeedbackController@index', 'as' => 'feedback']);
Route::post('/feedback',     ['uses' => 'FeedbackController@post',  'as' => 'feedback.post']);
Route::get('/feedback/done', ['uses' => 'FeedbackController@done',  'as' => 'feedback.done']);

Route::view('/help', 'help.index')->name('help');

Route::get('/account', ['uses' => 'AccountController@index', 'as' => 'account']);

Route::get('login',   ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
Route::post('login',  ['uses' => 'Auth\LoginController@login']);
Route::post('logout', ['uses' => 'Auth\LoginController@logout',        'as' => 'logout']);

Route::get('register',        ['uses' => 'Auth\RegisterController@showRegistrationForm', 'as' => 'register']);
Route::post('register',       ['uses' => 'Auth\RegisterController@register',             'as' => 'register.post']);
Route::get('account-created', ['uses' => 'Auth\RegisterController@registered',           'as' => 'register.done']);
Route::get('confirm-account', ['uses' => 'Auth\RegisterController@confirm',              'as' => 'register.confirm']);

Route::get('password/reset',         ['uses' => 'Auth\ForgotPasswordController@showLinkRequestForm', 'as' => 'password.request']);
Route::post('password/email',        ['uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail',  'as' => 'password.email']);
Route::get('password/requested',     ['uses' => 'Auth\ForgotPasswordController@requestedPassword',   'as' => 'password.requested']);
Route::get('password/reset/{token}', ['uses' => 'Auth\ResetPasswordController@showResetForm',        'as' => 'password.reset']);
Route::post('password/reset',        ['uses' => 'Auth\ResetPasswordController@reset']);
