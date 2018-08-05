@extends('layout.base-template', [
    'title' => 'Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'Meel.me', 'maxWidth' => 'max-w-sm'])

    <form method="post" class="panel max-w-xs mx-auto mt-8">
        {{ csrf_field() }}

        <label class="block">
            What?
            <input type="text" name="what" value="{{ old('what') }}" class="field" autocomplete="off" autofocus required>
        </label>

        <label class="block mt-8">
            When?
            <when-input default-when="now" feature="schedule"></when-input>
        </label>

        <button class="btn block ml-auto mt-4">Meel</button>

    </form>

@endsection
