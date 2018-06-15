<div class="flex justify-between items-center max-w-lg mx-auto pt-2">

    <span class="flex items-center">
        <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="ml-4 pt-1">{{ $title }}</h1>
    </span>

    <div>
        <a class="mr-4 text-grey-dark" href="{{ route('user.account') }}">account</a>

        <form method="post" class="inline-block" action="{{ route('logout') }}">
            {{ csrf_field() }}
            <button class="text-grey-dark">logout</button>
        </form>

    </div>
</div>
