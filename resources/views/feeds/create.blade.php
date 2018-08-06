@extends('layout.base-template', [
    'title' => 'New feed | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'New feed', 'maxWidth' => 'max-w-sm'])

    <form method="post" class="panel max-w-sm mx-auto mt-8 mb-16">
        {{ csrf_field() }}

        <h3>Feed url</h3>

        <input type="url" name="url" class="field" value="{{ old('url') }}" placeholder="https://example.com/feed" required>

        <small class="block -mt-2">Supported feed types: rss, atom</small>


        <h3 class="mt-8">Poll schedule</h3>
        <p class="text-sm">
            If you want new feed entries to be emailed to you on a schedule, you can enter one here.
            Leave this field empty if you want new entries to be emailed to you (almost) immediately.
            <br><br>
            The shortest custom schedule you can enter is <i>daily</i>.
        </p>

        <when-input default-when="every 15 minutes" feature="feed"></when-input>


        <h3 class="mt-4">Group new entries</h3>
        <p class="text-sm mb-4">
            A feed can have multiple new entries when it is polled.
            Uncheck this box if you always want to receive a separate email for each new feed entry.
            <br><br>
            If you leave this box checked, then new entries will be grouped in a single email.
        </p>

        <label class="inline-flex items-center select-none">
            <input type="hidden" name="group_new_entries" value="0">
            <input type="checkbox" name="group_new_entries" value="1" {{ old('group_new_entries', '1') === '1' ? 'checked' : '' }}>
            <span class="ml-2">Group new entries</span>
        </label>

        <button type="submit" class="btn block ml-auto mt-4">Add feed</button>

    </form>

@endsection
