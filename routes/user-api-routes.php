<?php

Route::post('human-when-interpretation/schedule', ['uses' => 'HumanInterpretation@schedule', 'as' => 'humanInterpretation.schedule']);
Route::post('human-when-interpretation/feed',     ['uses' => 'HumanInterpretation@feed',     'as' => 'humanInterpretation.feed']);


Route::get('schedules/upcoming',     ['uses' => 'SchedulesController@upcoming', 'as' => 'schedules.upcoming']);
Route::get('schedules/ended',        ['uses' => 'SchedulesController@ended',    'as' => 'schedules.ended']);
Route::put('schedule/{schedule}',    ['uses' => 'SchedulesController@put',      'as' => 'schedule.put'])->middleware('can:update,schedule');
Route::delete('schedule/{schedule}', ['uses' => 'SchedulesController@delete',   'as' => 'schedule.delete'])->middleware('can:delete,schedule');
