<div class="flex justify-between items-center max-w-lg mx-auto pt-2">

    <span class="flex items-center">
        <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="text-xl sm:text-3xl sm:ml-4 ml-2 pt-1">{{ $title }}</h1>
    </span>

    <div>
        @if(Route::currentRouteName() !== 'register')
            <a class="text-black mr-2 sm:mr-4" href="{{ route('register') }}">register</a>
        @endif

        @if(Route::currentRouteName() !== 'login')
            <a class="text-black" href="{{ route('login') }}">login</a>
        @endif
    </div>
</div>
