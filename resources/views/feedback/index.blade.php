@extends('layout.base-template', [
    'title' => 'Feedback | Meel.me',
])

@section('content')

    @include('layout.header-auth', ['title' => 'Feedback', 'maxWidth' => 'max-w-sm'])

    <div class="max-w-sm mx-auto">

        <p class="mt-8">
            You can use this form to send feedback, ask questions or request a new <i>what format</i>.
            Every message is read and greatly appreciated.
            <br><br>
            Your email will be included with the message, that way i can send you a reply if necessary.
        </p>

        <form method="post" class="mx-auto mt-2">
            {{ csrf_field() }}

            <label class="block mt-8">
                Feedback
                <textarea type="text" name="feedback" class="field h-32" maxlength="5000" autofocus required></textarea>
            </label>

            <button class="btn mt-4">Send</button>

        </form>

    </div>

@endsection
