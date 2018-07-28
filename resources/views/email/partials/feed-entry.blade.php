Title: {{ $feedEntry->title }}
<br>
Published at: {{ $feedEntry->publishedAt->setTimezone($feed->user->timezone) }}
<br>
Url: {{ $feedEntry->url }}