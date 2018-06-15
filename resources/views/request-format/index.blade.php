@extends('layout.base-template', [
    'title' => 'Request format | Meel.me',
])

@section('content')

    <div class="max-w-sm mx-auto">
        @include('layout.header', ['title' => 'Request format'])

        <p class="mt-8">
            If there is a <i>when format</i> you would like to use, but the site doesn't support yet, you can request it here.
            All requests will be reviewed, but no promises can be made about the actual implementation.
            <br><br>
            Try to provide at least one or two examples of how you would use the format.
        </p>

        <form method="post" class="mx-auto mt-2">
            {{ csrf_field() }}

            <label class="block mt-8">
                Format
                <input type="text" name="format" value="{{ old('format') }}" class="field" maxlength="255" autocomplete="off" autofocus required>
            </label>

            <button class="btn mt-4">Request</button>

        </form>

    </div>

@endsection
