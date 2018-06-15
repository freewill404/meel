@extends('layout.base-template', [
    'title' => 'Request format | Meel.me',
])

@section('content')

    <div class="max-w-xs mx-auto mt-2 text-center">

        <a class="text-black inline-block w-16 h-16" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="mt-4">Format requested!</h1>

        <p class="mt-4">
            Thank you for helping to improve the site
        </p>

        <a href="{{ route('requestFormat') }}" class="btn block mx-8 mt-8">Back</a>

    </div>

@endsection
