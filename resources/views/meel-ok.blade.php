@extends('layout.base-template', [
    'title' => 'Email schedule created! | Meel.me',
])

@section('content')

    <div class="max-w-xs mx-auto mt-2 text-center">

        <a class="text-black inline-block w-16 h-16" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="mt-4">Done!</h1>

        <p class="mt-4">
            Don't meel us, we'll meel you
        </p>

        <a href="/" class="btn block mx-8 mt-8">Back</a>

    </div>

@endsection
