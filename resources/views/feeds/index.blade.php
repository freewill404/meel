@extends('layout.base-template', [
    'title' => 'Feeds',
])

@section('content')
    @include('layout.header', ['title' => 'Feeds', 'maxWidth' => 'max-w-md'])

    <div class="max-w-sm mx-auto mt-8 mb-16">

        <div class="flex justify-between">
            <span></span>

            <a class="text-black text-sm" href="{{ route('user.feeds.create') }}">new</a>
        </div>


        @forelse($feeds as $feed)
            <div class="py-1 mb-3 panel">
                <input type="text" class="w-full bg-white mb-2" value="{{ $feed->url }}" disabled>

                <input placeholder="every 15 minutes" value="{{ $feed->when }}" class="w-full bg-white" disabled>

                <div class="flex items-center mt-4">

                    <span class="inline-block w-4 h-4" title="Emails sent">
                        @include('helpers.svg.envelope')
                    </span>

                    <span class="ml-1">{{ $feed->emails_sent }}x</span>

                    <a class="ml-4 w-4 h-4 cursor-pointer text-black" href="{{ route('user.feeds.show', $feed) }}" title="Edit">
                        @include('helpers.svg.pencil')
                    </a>

                    @if($feed->group_new_entries)
                        <span class="ml-4 w-4 h-4" title="Group new entries">
                            @include('helpers.svg.layer-group')
                        </span>
                    @endif

                </div>
            </div>
        @empty
            <div class="flex flex-col justify-center text-center mt-16">
                <div class="w-48 sm:w-64 mx-auto">
                    @include('helpers.drawings.missed-chances')
                </div>

                <div class="mt-8">
                    You haven't added any feeds {{ $noFeedsYet ? 'yet' : '' }}
                </div>

                <div class="mt-4">
                    <a class="text-black font-semibold" href="{{ route('user.feeds.create') }}">
                        {{ $noFeedsYet ? 'Add your first feed' : 'Add a feed' }}
                    </a>
                </div>
            </div>
        @endforelse

    </div>
@endsection
