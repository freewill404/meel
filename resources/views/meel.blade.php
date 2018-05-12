@extends('layout.base-template', [
    'title' => 'Meel.me',
])

@section('content')

    <div class="max-w-sm mx-auto flex justify-between items-center">
        <h1>Meel.me</h1>

        <div class="text-sm font-mono">
            <a class="text-grey" href="{{ route('account') }}">account</a>

            <span class="text-grey mx-1">|</span>

            <form method="post" class="inline-block" action="{{ route('logout') }}">
                {{ csrf_field() }}
                <button type="submit" class="text-grey">logout</button>
            </form>

        </div>

    </div>


    <form method="POST" class="max-w-xs mx-auto mt-2">
        {{ csrf_field() }}

        <label class="block mt-8">
            What?
            <input type="text" name="what" class="field" autofocus required>
        </label>

        <label class="block mt-8">
            When?
            <when-input></when-input>
        </label>

        <button class="btn mt-4">Meel!</button>

    </form>

@endsection
