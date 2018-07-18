<?php

namespace App\Http\Requests;

use App\Http\Rules\UsableWhen;
use App\Meel\Schedules\ScheduleFormat;

class MeelRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'what' => 'required|string|max:255',
            'when' => ['nullable', 'present', 'string', 'max:255', new UsableWhen],
        ];
    }

    public function getScheduleFormat()
    {
        return new ScheduleFormat($this->get('when'));
    }
}
