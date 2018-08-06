@extends('layout.base-template', [
    'title'       => 'Login | Meel.me',
    'description' => 'Login to your favorite self-emailing service',
])

@section('content')

    <div class="max-w-sm mx-auto mb-8">
        @include('layout.header', ['title' => 'Login'])
    </div>

    <form class="max-w-xs mx-auto panel" method="post" action="{{ route('login') }}">
        {{ csrf_field() }}
        <input type="hidden" name="remember" value="1">

        <label>
            Email
            <input class="field" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>

        <label>
            Password
            <input class="field" type="password" name="password" required>
        </label>

        @if($errors->count())
            <div class="text-center p-2 mt-8 bg-red-lighter rounded border-l-4 border-red">
                Invalid credentials
            </div>
        @endif

        <div class="flex justify-between items-center mt-8">
            <a class="text-sm text-grey-dark" href="{{ route('password.request') }}">Forgot password?</a>

            <button type="submit" class="btn block ml-auto">Login</button>
        </div>
    </form>

@endsection
