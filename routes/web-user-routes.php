<?php

Route::post('/',  ['uses' => 'MeelController@post', 'as' => 'meel.post']);
Route::get('/ok', ['uses' => 'MeelController@ok',   'as' => 'meel.ok']);

Route::get('/feedback',      ['uses' => 'FeedbackController@index', 'as' => 'feedback']);
Route::post('/feedback',     ['uses' => 'FeedbackController@post',  'as' => 'feedback.post']);
Route::get('/feedback/done', ['uses' => 'FeedbackController@done',  'as' => 'feedback.done']);

Route::get('/account',          ['uses' => 'AccountController@index',          'as' => 'account']);
Route::get('/account/edit',     ['uses' => 'AccountController@settings',       'as' => 'account.settings']);
Route::post('/account/edit/tz', ['uses' => 'AccountController@updateTimezone', 'as' => 'account.settings.updateTimezone']);
Route::post('/account/edit/pw', ['uses' => 'AccountController@updatePassword', 'as' => 'account.settings.updatePassword']);
Route::get('/more',             ['uses' => 'AccountController@more',           'as' => 'more']);
