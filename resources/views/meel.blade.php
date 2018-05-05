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
            <when-input></when-input>
        </label>

        <button class="btn mt-4">Meel!</button>

    </form>

@endsection
