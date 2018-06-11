@extends('layout.base-template', [
    'title' => 'Password reset requested | Meel.me',
])

@section('content')

    <div class="mx-auto mt-2 text-center">

        <a class="text-black inline-block w-16 h-16" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="mt-4">Password reset requested!</h1>

        <p class="mt-4 text-xl">
            Check your email to reset your password
        </p>

    </div>

@endsection
