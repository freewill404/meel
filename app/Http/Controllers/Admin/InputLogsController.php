<?php

namespace App\Http\Controllers\Admin;

use App\Models\InputLog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InputLogsController
{
    public function index()
    {
        $inputLogs = InputLog::all()
            ->mapToGroups(function (InputLog $inputLog) {
                return [$inputLog->session_id => $inputLog];
            })->map(function (Collection $collection) {
                return $collection->sortBy('id');
            });

        return view('admin.input-logs.index', [
            'inputLogs' => $inputLogs,
        ]);
    }

    public function delete(Request $request)
    {
        $sessionId = $request->get('session_id');

        InputLog::query()
            ->where('session_id', $sessionId)
            ->delete();

        return back();
    }
}
