@if($feedEntry->title && $feedEntry->url)
    <a href="{{ $feedEntry->url }}">{{ $feedEntry->title }}</a>
@else
    Title: {{ $feedEntry->title ?: '(no title)' }}
    <br>
    Url: {{ $feedEntry->url ?: '(no url)' }}
@endif

<br>
Published at: {{ $feedEntry->publishedAt->setTimezone($feed->user->timezone) }}
