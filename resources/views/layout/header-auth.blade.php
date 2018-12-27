<div class="flex justify-between items-center {{ $maxWidth }} mx-auto pt-2">

    <span class="flex items-center">
        <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="text-xl sm:text-3xl sm:ml-4 ml-2 pt-1">{{ $title }}</h1>
    </span>

    <span class="flex justify-between items-center">
        <a class="relative mr-4 w-4" title="Schedules" href="{{ route('user.schedules') }}">
            @include('helpers.svg.envelope')

            @if(Route::is('user.schedules*'))
                <div class="absolute -ml-1 w-6 border-b-4 border-yellow-dark"></div>
            @endif
        </a>

        <a class="mr-4 w-4" title="Feeds" href="{{ route('user.feeds') }}">
            @include('helpers.svg.feed')

            @if(Route::is('user.feeds*'))
                <div class="absolute -ml-1 w-6 border-b-4 border-yellow-dark"></div>
            @endif
        </a>

        <a class="w-4" title="Account" href="{{ route('user.account') }}">
            @include('helpers.svg.cog')

            @if(Route::is('user.account*'))
                <div class="absolute -ml-1 w-6 border-b-4 border-yellow-dark"></div>
            @endif
        </a>

    </span>

</div>
