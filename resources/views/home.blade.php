@extends('layout.empty-template', [
    'title' => 'Meel.me',
    'description' => 'Send yourself scheduled recurring emails. Add rss or atom feeds to have new entries emailed to you automatically.',
])

@section('body')
<body class="bg-grey-lightest">

    <div class="container mx-auto px-4 py-2">
        <div class="flex justify-between items-center mx-auto mt-2 mb-10">
            <h1 class="text-3xl">
                <a class="text-black" href="{{ route('home') }}">meel.me</a>
            </h1>

            <div>
                <a class="text-black mr-4" href="{{ route('register') }}">register</a>

                <a class="text-black" href="{{ route('login') }}">login</a>
            </div>
        </div>



        <div class="flex flex-col max-w-sm mx-auto text-center">
            <h2 class="text-5xl mt-4">Email yourself</h2>

            <a href="{{ route('register') }}" class="btn inline-block mx-auto uppercase tracking-wide mt-12">Get started</a>
        </div>



        <div class="flex justify-between max-w-lg mx-auto mt-16">
            <div class="flex flex-col w-48 text-center">
                <span class="w-10 mx-auto">@include('helpers.svg.envelope')</span>

                <h2 class="text-lg my-2">Email yourself</h2>

                Remember important appointments and keep yourself on track.

                <a href="{{ route('about') }}" class="italic font-bold text-black mt-2">Learn more</a>
            </div>


            <div class="flex flex-col w-48 text-center">
                <span class="w-10 mx-auto">@include('helpers.svg.clock')</span>

                <h2 class="text-lg my-2">Schedules</h2>

                Schedule emails to receive them exactly when you want to.

                <a href="{{ route('schedules-intro') }}" class="italic font-bold text-black mt-2">Learn more about schedules</a>
            </div>


            <div class="flex flex-col w-48 text-center">
                <span class="w-10 mx-auto">@include('helpers.svg.feed')</span>

                <h2 class="text-lg my-2">Email based rss reader</h2>

                Add feeds and stay up to date with content that you care about.

                <a href="{{ route('feeds-intro') }}" class="italic font-bold text-black mt-2">Learn more about feeds</a>
            </div>
        </div>
    </div>


    <div class="bg-white mt-16 py-4">
        <div class="container flex justify-center mx-auto px-4 py-2">

            <div class="w-48 mr-16">
                @include('helpers.svg.github')
            </div>

            <div class="max-w-xs">
                <h2 class="text-3xl mb-4">Open source</h2>
                <p>
                    Meel.me is completely open-source and licensed under the terms of the MIT license.
                    <br><br>
                    Head over to <a class="font-bold" href="https://github.com/SjorsO/meel" target="_blank">Github</a> to read the code and contribute.
                </p>
            </div>

        </div>
    </div>


    <div class="mb-32">&nbsp;</div>

</body>
@endsection
