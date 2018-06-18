<div class="flex justify-between items-center max-w-lg mx-auto pt-2">

    <span class="flex items-center">
        <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">
            @include('helpers.svg.logo')
        </a>

        <h1 class="text-xl sm:text-3xl sm:ml-4 ml-2 pt-1">{{ $title }}</h1>
    </span>

    <div class="flex flex-wrap">
        <a class="mr-2 sm:mr-4 text-grey-dark" href="{{ route('user.account') }}">account</a>

        <a class="mr-2 sm:mr-4 text-grey-dark" href="{{ route('user.account.settings') }}">settings</a>

        <form method="post" class="inline-block" action="{{ route('logout') }}">
            {{ csrf_field() }}
            <button class="text-grey-dark">logout</button>
        </form>
    </div>
</div>
