@extends('layout.base-template', [
    'title' => 'Introduction to schedules | Meel.me',
    'description' => 'Schedules can be used to email yourself exactly when you want to. This page is a basic introduction about how schedules work.',
])

@section('content')

    <div class="flex justify-between items-center mx-auto mt-2 mb-10">
        <h1 class="text-3xl">
            <a class="text-black" href="{{ route('home') }}">meel.me</a>
        </h1>

        <div>
            <a class="text-black mr-4" href="{{ route('register') }}">register</a>

            <a class="text-black" href="{{ route('login') }}">login</a>
        </div>
    </div>


    <h2 class="text-3xl mt-4 mb-2">An introduction to schedules</h2>
    <p class="text-xl max-w-md leading-normal ml-6">
        Schedules allow you to decide when you want to receive emails.
        You define a schedule in normal English, that makes them straight-forward and simple to use.
        Take a look at the following scheduled email:
        <br>
        <br>
        <strong>What?</strong> Call grandma
        <br>
        <strong>When?</strong> Tomorrow at 15:00
        <br>
        <br>
        The next day, when the clock strikes three, you'll receive an email and you'll have no excuse not to call her.
    </p>

    <h3 class="text-2xl mt-8 mb-2">Recurring schedules</h3>
    <a id="recurring"></a>
    <p class="text-xl max-w-md leading-normal ml-6">
        Some chores are important, but remembering to do them regularly can be difficult.
        That's where recurring schedules come into play.
        Below is an example schedule that could benefit anyone:
        <br>
        <br>
        <strong>What?</strong> Clean the house
        <br>
        <strong>When?</strong> Every first Saturday of the month
        <br>
        <br>
        Recurring schedules keep your house clean and your life on track.
    </p>

    <h3 class="text-2xl mt-8 mb-2">More in-depth</h3>
    <p class="text-xl max-w-md leading-normal ml-6">
        For the most part, schedules should be easy to figure out.
        However, if you would like more details about schedules, head over to the <a href="{{ route('help') }}">schedule documentation page.</a>
    </p>

    <h3 class="text-2xl mt-8 mb-2">Feeds</h3>
    <p class="text-xl max-w-md leading-normal ml-6">
        Schedules can also be used with rss feeds to receive new content when you want it to.
        <br><br>
        <a href="{{ route('feeds-intro') }}">Learn more about feeds »</a>
    </p>

    <div class="mb-32"></div>

@endsection
