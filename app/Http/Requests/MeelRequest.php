<?php

namespace App\Http\Requests;

use App\Http\Rules\UsableWhen;
use App\Meel\EmailScheduleFormat;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MeelRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'what' => 'required|string|max:255',
            'when' => ['nullable', 'present', 'string', 'max:255', new UsableWhen],
        ];
    }

    public function getScheduleFormat()
    {
        return new EmailScheduleFormat($this->get('when'));
    }

    /**
     * @param null $guard
     *
     * @return User
     */
    public function user($guard = null)
    {
        return parent::user($guard);
    }
}
