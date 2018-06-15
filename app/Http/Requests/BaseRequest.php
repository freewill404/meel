<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    abstract public function rules();

    /**
     * @param null $guard
     *
     * @return User
     */
    public function user($guard = null)
    {
        // Overridden for better IDE intellisense
        return parent::user($guard);
    }
}
