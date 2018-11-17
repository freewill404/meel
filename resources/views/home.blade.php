@extends('layout.empty-template', [
    'title' => 'Meel.me',
    'description' => 'Send yourself scheduled recurring emails. Add rss or atom feeds to have new entries emailed to you automatically.',
])

@section('body')
<body class="bg-grey-lightest">

    <div class="container mx-auto px-4 pt-2">
        <div class="flex justify-between items-center mx-auto mt-2 mb-10">
            <span class="text-3xl">
                <a class="text-black" href="{{ route('home') }}">meel.me</a>
            </span>

            <div>
                <a class="text-black mr-4" href="{{ route('register') }}">register</a>

                <a class="text-black" href="{{ route('login') }}">login</a>
            </div>
        </div>



        <div class="flex flex-col mx-auto text-center">
            <h1 class="text-5xl mb-4 font-extrabold">Email yourself</h1>

            <h2 class="font-normal">Sending yourself email has never been this easy</h2>
        </div>



        <div class="flex justify-between max-w-lg mx-auto mt-24">
            <div class="flex flex-col w-48 text-center">
                <span class="w-10 mx-auto">@include('helpers.svg.envelope')</span>

                <h2 class="text-xl my-2 font-semibold">Email yourself</h2>
                <p class="text-lg">
                    Use email as a to-do list to keep yourself on track
                </p>
                <a href="{{ route('about') }}" class="italic text-black mt-4">Learn more</a>
            </div>


            <div class="flex flex-col w-48 text-center">
                <span class="w-10 mx-auto">@include('helpers.svg.clock')</span>

                <h2 class="text-xl my-2 font-semibold">Schedules</h2>
                <p class="text-lg">
                    Easily schedule recurring emails for the future you
                </p>
                <a href="{{ route('schedules-intro') }}" class="italic text-black mt-4">Learn more about schedules</a>
            </div>


            <div class="flex flex-col w-48 text-center">
                <span class="w-10 mx-auto">@include('helpers.svg.feed')</span>

                <h2 class="text-xl my-2 font-semibold">Email-based RSS reader</h2>
                <p class="text-lg">
                    Receive content you care about by email
                </p>
                <a href="{{ route('feeds-intro') }}" class="italic text-black mt-4">Learn more about feeds</a>
            </div>
        </div>

    </div>


    <div class="py-8 my-12 text-center bg-blue-lightest border-t-2 border-b-2">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center max-w-md mt-4 mx-auto">
                <div class="flex flex-col text-center">
                    <span class="w-8 mx-auto">@include('helpers.svg.schedule-created')</span>
                    <span class="text-2xl my-2 font-semibold">{{ $schedulesCreated }}</span>
                    schedules created
                </div>

                <div class="flex flex-col text-center">
                    <span class="w-8 mx-auto">@include('helpers.svg.mail-sent')</span>
                    <span class="text-2xl my-2 font-semibold">{{ $emailsSent }}</span>
                    emails sent
                </div>

                <div class="flex flex-col text-center">
                    <span class="w-8 mx-auto">@include('helpers.svg.feed-checks')</span>
                    <span class="text-2xl my-2 font-semibold">{{ $feedPolls }}</span>
                    feed polls
                </div>
            </div>

            <div class="text-grey text-sm text-right mt-4 pr-8">since june 2018</div>
        </div>

    </div>

    <div class="container mx-auto px-4 mb-12">

        <div class="flex justify-center items-center mx-auto px-4">
            <div class="w-48 mr-16">
                @include('helpers.svg.github')
            </div>

            <div class="max-w-xs">
                <h2 class="text-3xl mb-4">Open source</h2>
                <p class="text-lg">
                    Meel.me is open-source and licensed under the terms of the MIT license.
                    <br><br>
                    Head over to <a class="font-bold" href="https://github.com/SjorsO/meel" target="_blank">Github</a> to contribute!
                </p>
            </div>
        </div>

    </div>

</body>
@endsection
