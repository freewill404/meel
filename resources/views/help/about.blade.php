@extends('layout.base-template', [
    'title' => 'Introduction to emailing yourself | Meel.me',
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


    <h2 class="text-3xl mt-4 mb-2">An introduction to emailing yourself</h2>
    <p class="text-xl max-w-md leading-normal ml-6">
        Emails are great: they are lightweight, they work on every device, and they are easy to delete.
        You can use emails as to-do list items, leave them unread if you aren't done with them yet, delete them as soon as you are.
        <br><br>
        Meel.me is the easiest way to send yourself emails, and helps you remember what is important to you.
    </p>

    <h3 class="text-2xl mt-8 mb-2">Inbox zero</h3>
    <p class="text-xl max-w-md leading-normal ml-6">
        Emailing yourself works best when you take an inbox zero approach at email management.
        That basically means that your inbox should always be empty.
        Delete emails when you are done with them, don't let unread emails pile up.
        You can archive important emails so you can look them up later, but you'll probably never need to.
    </p>

    <h3 class="text-2xl mt-8 mb-2">Remind the future you</h3>
    <p class="text-xl max-w-md leading-normal ml-6">
        With meel.me you can create scheduled emails that will arrive exactly when you want them to.
        <br><br>
        <a href="{{ route('schedules-intro') }}">Learn more about schedules »</a>
    </p>


    <div class="mb-32"></div>

@endsection
