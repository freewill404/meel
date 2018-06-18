@extends('layout.base-template', [
    'title' => 'Meel.me',
])

@section('content')

    <div class="max-w-sm mx-auto flex justify-between items-center">
        <span class="flex items-center">
            <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
                @include('helpers.svg.logo')
            </a>

            <h1 class="text-xl sm:text-3xl sm:ml-4 ml-2 pt-1">Meel.me</h1>
        </span>

        <a class="text-grey-dark" href="{{ route('user.account') }}">account</a>
    </div>


    <form method="post" class="max-w-xs mx-auto mt-2">
        {{ csrf_field() }}

        <label class="block mt-8">
            What?
            <input type="text" name="what" value="{{ old('what') }}" class="field" autocomplete="off" autofocus required>
        </label>

        <label class="block mt-8">
            When?
            <when-input></when-input>
        </label>

        <button class="btn mt-4">Meel</button>

    </form>

@endsection
