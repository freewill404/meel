<?php

Route::post('/',  ['uses' => 'MeelController@post', 'as' => 'meel.post']);
Route::get('/ok', ['uses' => 'MeelController@ok',   'as' => 'meel.ok']);

Route::get('/more', ['uses' => 'MoreController@more', 'as' => 'more']);

Route::get('/request-format',      ['uses' => 'RequestFormatController@index', 'as' => 'requestFormat']);
Route::post('/request-format',     ['uses' => 'RequestFormatController@post',  'as' => 'requestFormat.post']);
Route::get('/request-format/done', ['uses' => 'RequestFormatController@done',  'as' => 'requestFormat.done']);

Route::get('/feedback',      ['uses' => 'FeedbackController@index', 'as' => 'feedback']);
Route::post('/feedback',     ['uses' => 'FeedbackController@post',  'as' => 'feedback.post']);
Route::get('/feedback/done', ['uses' => 'FeedbackController@done',  'as' => 'feedback.done']);

Route::get('/account', ['uses' => 'AccountController@index', 'as' => 'account']);
