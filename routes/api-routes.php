<?php

Route::post('human-when-interpretation', ['uses' => 'HumanInterpretation', 'as' => 'humanInterpretation']);

Route::post('request-when-format', ['uses' => 'RequestFormat', 'as' => 'requestWhenFormat']);

Route::put('schedule/{schedule}',    ['uses' => 'ScheduleController@put',    'as' => 'schedule.put'   ])->middleware('can:update,schedule');
Route::delete('schedule/{schedule}', ['uses' => 'ScheduleController@delete', 'as' => 'schedule.delete'])->middleware('can:delete,schedule');
