<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Support\Enums\Timezones;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'timezones' => Timezones::all(),
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'    => 'required|string|email|max:255|unique:users',
            'timezone' => Timezones::required(),
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'email'    => $data['email'],
            'timezone' => $data['timezone'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
