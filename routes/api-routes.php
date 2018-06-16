<?php

Route::post('human-when-interpretation', ['uses' => 'WhenController@humanInterpretation', 'as' => 'humanInterpretation']);

Route::post('request-when-format', ['uses' => 'RequestFormat', 'as' => 'requestWhenFormat']);

Route::put('email-schedule/{emailSchedule}',    ['uses' => 'EmailScheduleController@put',    'as' => 'emailSchedule.put'   ])->middleware('can:update,emailSchedule');
Route::delete('email-schedule/{emailSchedule}', ['uses' => 'EmailScheduleController@delete', 'as' => 'emailSchedule.delete'])->middleware('can:delete,emailSchedule');
