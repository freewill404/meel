<div class="flex justify-between items-center max-w-lg mx-auto pt-2">

    <span class="flex items-center">
        <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="ml-4 pt-1">{{ $title }}</h1>
    </span>

    <div>
        <a class="text-black mr-4" href="{{ route('register') }}">register</a>

        <a class="text-black" href="{{ route('login') }}">login</a>
    </div>
</div>
