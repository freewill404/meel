<?php

Route::post('human-when-interpretation/schedule', ['uses' => 'HumanInterpretation@schedule', 'as' => 'humanInterpretation.schedule']);
Route::post('human-when-interpretation/feed',     ['uses' => 'HumanInterpretation@feed',     'as' => 'humanInterpretation.feed']);


Route::put('schedule/{schedule}',    ['uses' => 'ScheduleController@put',    'as' => 'schedule.put'   ])->middleware('can:update,schedule');
Route::delete('schedule/{schedule}', ['uses' => 'ScheduleController@delete', 'as' => 'schedule.delete'])->middleware('can:delete,schedule');
