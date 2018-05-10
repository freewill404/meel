@extends('layout.base-template', [
    'title'       => 'Meel.me | Schedule and send (recurring) emails to yourself',
    'description' => 'SEO description',
])

@section('content')

    <div class="max-w-sm mx-auto mt-2">

        <div class="flex justify-between items-center">
            <h1>Meel.me</h1>

            <div class="text-sm font-mono">
                <a href="{{ route('register') }}">register</a>

                <span class="mx-1">|</span>

                <a href="{{ route('login') }}">login</a>
            </div>

        </div>

        <p class="mt-4 text-lg">
            Schedule and send (recurring) emails to yourself
        </p>

    </div>

@endsection
