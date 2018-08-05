@extends('layout.base-template', [
    'title' => 'Update feed | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'Update feed', 'maxWidth' => 'max-w-sm'])

    <div class="max-w-sm mx-auto mt-8 mb-16">

        <form method="post" id="update-form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <h3 class="mt-8">Feed url</h3>

            <input type="url" name="url" class="field" value="{{ old('url', $feed->url) }}" placeholder="https://example.com/feed" required>

            <small class="block -mt-2">Supported feed types: rss, atom</small>


            <h3 class="mt-8">Poll schedule</h3>
            <p class="text-sm">
                If you want new feed entries to be emailed to you on a schedule, you can enter one here.
                Leave this field empty if you want new entries to be emailed to you (almost) immediately.
                <br><br>
                The shortest custom schedule you can enter is <i>daily</i>.
            </p>

            <when-input default-when="every 15 minutes" initial-when="{{ $feed->when }}" feature="feed"></when-input>


            <h3 class="mt-4">Group new entries</h3>
            <p class="text-sm mb-4">
                A feed can have multiple new entries when it is polled.
                Uncheck this box if you always want to receive a separate email for each new feed entry.
                <br><br>
                If you leave this box checked, then new entries will be grouped in a single email.
            </p>

            <label class="inline-flex items-center select-none">
                <input type="hidden" name="group_new_entries" value="0">
                <input type="checkbox" name="group_new_entries" value="1" {{ old('group_new_entries', $feed->group_new_entries) ? 'checked' : '' }}>
                <span class="ml-2">Group new entries</span>
            </label>

        </form>

        <div class="flex justify-between mt-8">
            <form method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this feed?')">Delete feed</button>
            </form>

            <button type="submit" class="btn" form="update-form">Update feed</button>
        </div>

    </div>

@endsection
