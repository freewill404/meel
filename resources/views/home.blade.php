@extends('layout.base-template', [
    'title'       => 'Meel.me | Schedule and send (recurring) emails to yourself',
    'description' => 'Never forget anything by mailing yourself. Don\'t clog up your agenda with reminders, fill up your gmail with emails instead!',
])

@section('content')

    <div class="max-w-sm mx-auto mt-2 mb-16">

        <div class="flex justify-between items-center">
            <span class="flex items-center">
                <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
                    @include('helpers.svg.logo')
                </a>

                <h1 class="text-xl sm:text-3xl sm:ml-4 ml-2 pt-1">Meel.me</h1>
            </span>

            <div>
                <a class="text-black mr-4" href="{{ route('register') }}">register</a>

                <a class="text-black" href="{{ route('login') }}">login</a>
            </div>
        </div>

        <meel-example></meel-example>

        <h3 class="mt-8">What</h3>
        <p>
            Meel.me lets you send (recurring) emails to yourself.
            <br><br>
            Use emails as reminders that show up on your phone and computer.

            Use recurring emails to remind yourself of the things you know you should be doing,
            such as going to the dentist at least once a year, or cleaning the house every month.
        </p>

        <h3 class="mt-8">Why</h3>
        <p>
            Emails are convenient and simple. They show up on all your devices, even if you don't use Gmail or other Google products.
            <br><br>

            <strong>Why not use Google reminders?</strong>
            <br>
            Emails are more convenient than Google reminders.
            Reminders fill up your agenda (unless you filter them),
            they don't show up on your computer,
            they can't be easily dismissed,
            and the only way to quickly create them is by yelling "OK Google" at your phone, which is less than ideal in public.
        </p>

        <h3 class="mt-8">How</h3>
        <p>
            Creating an email schedule is easy.
            The time the email should be sent is interpreted from normal english.
            A detailed guide can be found on the <a class="font-bold" href="{{ route('help') }}">help page.</a>
            <br><br>
            <a class="font-bold" href="{{ route('register') }}">Register an account</a> and start emailing yourself today!
        </p>

    </div>

@endsection
