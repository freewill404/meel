@extends('layout.base-template', [
    'title' => 'Feeds | Meel.me',
])

@section('content')

    @include('layout.header', ['title' => 'Feeds', 'maxWidth' => 'max-w-md'])

    <div class="max-w-md mx-auto mt-8 mb-16">

        <div class="flex justify-between">
            <h2 class="mb-4">Feeds</h2>

            <a class="text-black text-sm" href="{{ route('user.feeds.create') }}">new</a>
        </div>

        <div class="sm:pl-4">
            @forelse($feeds as $feed)
                <div class="py-1 mb-3 panel">
                    <input type="text" class="w-full bg-white mb-2" value="{{ $feed->url }}" disabled>

                    <input placeholder="every 15 minutes" value="{{ $feed->when }}" class="w-full bg-white" disabled>

                    <div class="flex items-center mt-4">

                        <span class="inline-block w-4 h-4" title="Emails sent">
                            <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1664 1504v-768q-32 36-69 66-268 206-426 338-51 43-83 67t-86.5 48.5-102.5 24.5h-2q-48 0-102.5-24.5t-86.5-48.5-83-67q-158-132-426-338-37-30-69-66v768q0 13 9.5 22.5t22.5 9.5h1472q13 0 22.5-9.5t9.5-22.5zm0-1051v-24.5l-.5-13-3-12.5-5.5-9-9-7.5-14-2.5h-1472q-13 0-22.5 9.5t-9.5 22.5q0 168 147 284 193 152 401 317 6 5 35 29.5t46 37.5 44.5 31.5 50.5 27.5 43 9h2q20 0 43-9t50.5-27.5 44.5-31.5 46-37.5 35-29.5q208-165 401-317 54-43 100.5-115.5t46.5-131.5zm128-37v1088q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-1088q0-66 47-113t113-47h1472q66 0 113 47t47 113z"></path></svg>
                        </span>

                        <span class="ml-1">{{ $feed->emails_sent }}x</span>

                        <a class="ml-4 w-4 h-4 cursor-pointer text-black" href="{{ route('user.feeds.show', $feed) }}" title="Edit">
                            <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z"></path></svg>
                        </a>

                        @if($feed->group_new_entries)
                        <span class="ml-4 w-4 h-4" title="Group new entries">
                            <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-57.87-26.23-161.86 73.37c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.29 337.87 12.41 364.1c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z"></path></svg>
                        </span>
                        @endif

                    </div>
                </div>
            @empty
                <p>
                    You haven't added any feeds yet.
                </p>
            @endforelse
        </div>

    </div>

@endsection
