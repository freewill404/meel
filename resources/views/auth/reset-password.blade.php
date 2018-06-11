@extends('layout.base-template', [
    'title' => 'Reset Password | Meel.me',
])

@section('content')

    <div class="mx-auto mt-2 text-center">

        <a class="inline-block text-black w-16 h-16" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <form method="post" class="max-w-xs mx-auto mt-8" action="{{ route('password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <label>
                Email address
                <input id="email" type="email" class="field" name="email" value="{{ $email or old('email') }}" required autofocus>
            </label>

            <label>
                New password
                <input id="password" type="password" class="field" name="password" required>
            </label>

            <label>
                Confirm new password
                <input id="password-confirm" type="password" class="field" name="password_confirmation" required>
            </label>


            <button type="submit" class="btn block mx-auto mt-8">Reset Password</button>

        </form>

    </div>

    <div class="max-w-sm mx-auto text-sm">
        @if($errors->count())
            <div class="p-2 mt-8 bg-red-lighter rounded border-l-4 border-red">
                <strong class="block mb-2">The following errors occurred:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

@endsection
