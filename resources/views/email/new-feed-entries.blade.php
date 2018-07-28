{{ count($feedEntries) }} new feed entries for {{ $feed->url }}

@foreach($feedEntries as $feedEntry)
    <br>
    <br>

    @include('email.partials.feed-entry', compact($feedEntry))
@endforeach
