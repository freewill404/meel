<div class="flex justify-between items-center {{ $maxWidth }} mx-auto pt-2">

    <span class="flex items-center">
        <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="text-xl sm:text-3xl sm:ml-4 ml-2 pt-1">{{ $title }}</h1>
    </span>

    <span class="flex justify-between items-center">
        <a class="mr-4 text-grey-dark w-4" title="Schedules" href="{{ route('user.schedules') }}">
            @include('helpers.svg.envelope')
        </a>

        <a class="mr-4 text-grey-dark w-4" title="Feeds" href="{{ route('user.feeds') }}">
            @include('helpers.svg.feed')
        </a>

        @if(Route::is('user.account'))
            <form method="post" class="inline-block" action="{{ route('logout') }}">
                {{ csrf_field() }}
                <button class="text-grey-dark w-4" title="Logout">
                    @include('helpers.svg.sign-out')
                </button>
            </form>
        @else
            <a class="text-grey-dark w-4" title="Account" href="{{ route('user.account') }}">
                @include('helpers.svg.cog')
            </a>
        @endif

    </span>

</div>
