@extends('layout.base-template', [
    'title'       => 'Request password reset | Meel.me',
    'description' => 'Forgot your password? We\'ll mail you a new one!',
])

@section('content')

    <div class="max-w-sm mx-auto mb-8">
        @include('layout.header', ['title' => 'Password Reset'])
    </div>

    <div class="max-w-xs mx-auto mt-8">

        <form method="post" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <label>
                Email
                <input type="email" class="field" name="email" value="{{ old('email') }}" required>
            </label>

            <button type="submit" class="btn block ml-auto">Request password reset</button>
        </form>

        @if($errors->count())
            <div class="text-center p-2 mt-8 bg-red-lighter rounded">
                No account found with that email
            </div>
        @endif

    </div>



@endsection
