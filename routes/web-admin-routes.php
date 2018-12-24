<?php

Route::get('/', ['uses' => 'DashboardController@index', 'as' => 'dashboard']);


Route::get('/input-logs',    ['uses' => 'InputLogsController@index',  'as' => 'inputLogs.index']);
Route::delete('/input-logs', ['uses' => 'InputLogsController@delete', 'as' => 'inputLogs.delete']);
