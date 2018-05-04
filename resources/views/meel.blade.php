@extends('layout.base-template', [
    'title'       => 'SEO title',
    'description' => 'SEO description',
])

@section('content')

    <form method="POST" class="max-w-sm mx-auto mt-2">

        <h1>Meel.me</h1>

        <label class="block mt-8">
            What?
            <input type="text" name="what" class="field" autofocus required>
        </label>

        <label class="block mt-8">
            When?
            <input type="text" name="when" class="field" placeholder="now">
        </label>

        <button class="btn mt-8">Meel!</button>

    </form>

@endsection
