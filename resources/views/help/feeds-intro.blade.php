@extends('layout.base-template', [
    'title' => 'Introduction to feeds | Meel.me',
    'description' => 'Add feeds and have new entries emailed to you. Works with rss and atom feeds. Use schedules to receive new entries when it best suits you.',
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


    <h2 class="text-3xl mt-4 mb-2">An introduction to feeds</h2>
    <p class="text-xl max-w-md leading-normal sm:ml-6">
        Apart from being a self-emailing service, Meel.me is also an email based rss reader.
        Email is great, rss feeds are great, why not combine them?
        You can add feeds to your account and have new entries delivered to your inbox as soon as they are published.
    </p>

    <h3 class="text-2xl mt-8 mb-2">Scheduling feeds</h3>
    <p class="text-xl max-w-md leading-normal sm:ml-6">
        You can use <a href="{{ route('schedules-intro') }}#recurring">recurring schedules</a> to receive new entries when it best suits you.
        For example, if you enjoy reading Seth's daily blogs during your Sunday lunch, you can add a feed like this:
        <br>
        <br>
        <strong>Feed?</strong> <a href="https://seths.blog/feed/" target="_blank" rel="nofollow">https://seths.blog/feed/</a>
        <br>
        <strong>When?</strong> Every Sunday at 12:00
        <br>
        <br>
        You'll receive all the entries you haven't read yet every Sunday afternoon.
    </p>

    <h3 class="text-2xl mt-8 mb-2">Give it a try</h3>
    <p class="text-xl max-w-md leading-normal sm:ml-6">
        Why not register an account and give meel.me a try?
        <br><br>
        <a href="{{ route('register') }}">Get started »</a>
    </p>


    <div class="mb-32"></div>

@endsection
