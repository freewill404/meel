@extends('layout.base-template', [
    'title'       => 'Meel.me | Email yourself',
    'description' => 'Send yourself scheduled recurring emails. Add rss or atom feeds to have new entries emailed to you automatically.',
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



        <div class="panel flex mt-8">
            <span class="w-24 mr-4">@include('helpers.svg.envelope')</span>

            <span>
                <h2 class="inline-block text-base">Send emails to yourself</h2> using a convenient written schedule syntax.
                Make scheduled emails recurring to keep reminding yourself of things you know you should be doing.
            </span>
        </div>


        <div class="panel flex mt-8">
            <span class="w-24 mr-4">@include('helpers.svg.feed')</span>

            <span>
                <h2 class="inline-block text-base">Have new rss or atom feed entries emailed to you.</h2>
                Assign a schedule to the feeds to only receive new entries at a specific time.
            </span>
        </div>


        <div class="panel flex mt-8">
            <span class="w-24 mr-4">@include('helpers.svg.clock')</span>

            <span>
                <h3 class="inline-block text-base">Use convenient schedules</h3> to receive emails exactly when you want to.
                Schedules are flexible and easy to write.
                <a class="text-black underline" href="{{ route('help') }}">Learn more about written schedules.</a>
            </span>
        </div>


        <div class="panel flex mt-8">
            <span class="w-16 mr-4">@include('helpers.svg.github')</span>

            <span>
                <h3 class="inline-block text-base">Meel.me is open source.</h3>
                You can view the code and contribute <a class="text-black underline" href="https://github.com/SjorsO/meel" target="_blank">on Github</a>!
            </span>
        </div>

    </div>

@endsection
