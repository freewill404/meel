<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailSchedule;
use Illuminate\Http\Request;

class EmailScheduleController extends Controller
{
    public function put(Request $request, EmailSchedule $emailSchedule)
    {
        $values = $request->validate([
            'what' => 'required|string|max:255',
        ]);

        $emailSchedule->update($values);
    }

    public function delete(EmailSchedule $emailSchedule)
    {
        $emailSchedule->delete();
    }
}
