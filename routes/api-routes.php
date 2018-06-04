<?php

Route::post('human-when-interpretation', ['uses' => 'WhenController@humanInterpretation', 'as' => 'humanInterpretation']);

Route::post('request-when-format', ['uses' => 'RequestFormat', 'as' => 'requestWhenFormat']);
