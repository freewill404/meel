@extends('layout.base-template', [
    'title'       => 'Register an account | Meel.me',
    'description' => 'Register today to start mailing yourself',
])

@section('content')

    <form class="max-w-xs bg-white border mx-auto p-2" method="post" action="{{ route('register.post') }}">
        <h2 class="mb-4">Register | Meel.me</h2>
        {{ csrf_field() }}

        <label>
            Email
            <input class="field" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>

        <label class="block">
            Timezone
            <select name="timezone" class="field">
                @foreach($timezones as $timezone)
                    <option value="{{ $timezone }}" {{ $timezone === 'Europe/Amsterdam' ? 'selected' : '' }}>{{ $timezone }}</option>
                @endforeach
            </select>
        </label>

        <label>
            Password
            <input class="field" type="password" name="password" required>
        </label>

        <label>
            Repeat password
            <input class="field" type="password" name="password_confirmation" required>
        </label>

        <button type="submit" class="btn block ml-auto mt-4">Register</button>
    </form>

@endsection
