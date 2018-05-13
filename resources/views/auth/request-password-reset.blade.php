@extends('layout.base-template', [
    'title'       => 'Request password reset | Meel.me',
    'description' => 'Forgot your password? We\'ll mail you a new one!',
])

@section('content')

    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif

    <div class="max-w-xs mx-auto mt-8">

        <form method="post" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <label>
                E-Mail Address
                <input type="email" class="field" name="email" value="{{ old('email') }}" required>
            </label>

            <button type="submit" class="btn">Send Password Reset Link</button>
        </form>

    </div>

@endsection
