<?php

Route::get('/schedules', ['uses' => 'SchedulesController@index', 'as' => 'schedules']);
Route::post('/',         ['uses' => 'SchedulesController@post',  'as' => 'schedules.post']);
Route::get('/ok',        ['uses' => 'SchedulesController@ok',    'as' => 'schedules.ok']);


Route::get('/feeds',           ['uses' => 'FeedsController@index',   'as' => 'feeds']);
Route::get('/feeds/new',       ['uses' => 'FeedsController@create',  'as' => 'feeds.create']);
Route::post('/feeds/new',      ['uses' => 'FeedsController@store',   'as' => 'feeds.store']);
Route::get('/feeds/{feed}',    ['uses' => 'FeedsController@show',    'as' => 'feeds.show']  )->middleware('can:view,feed');
Route::put('/feeds/{feed}',    ['uses' => 'FeedsController@update',  'as' => 'feeds.update'])->middleware('can:update,feed');
Route::delete('/feeds/{feed}', ['uses' => 'FeedsController@delete',  'as' => 'feeds.delete'])->middleware('can:delete,feed');


Route::get('/feedback',      ['uses' => 'FeedbackController@index', 'as' => 'feedback']);
Route::post('/feedback',     ['uses' => 'FeedbackController@post',  'as' => 'feedback.post']);
Route::get('/feedback/done', ['uses' => 'FeedbackController@done',  'as' => 'feedback.done']);

Route::get('/account',          ['uses' => 'AccountController@index',          'as' => 'account']);
Route::post('/account/edit/tz', ['uses' => 'AccountController@updateTimezone', 'as' => 'account.settings.updateTimezone']);
Route::post('/account/edit/pw', ['uses' => 'AccountController@updatePassword', 'as' => 'account.settings.updatePassword']);
Route::get('/more',             ['uses' => 'AccountController@more',           'as' => 'more']);
