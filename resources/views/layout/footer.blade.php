@auth
    <div class="max-w-sm mx-auto flex justify-between text-sm absolute pin-b pin-l pin-r px-4 pb-4">
        <a class="block text-grey-dark w-4 h-4" href="/">
            @include('helpers.svg.logo')
        </a>

        <a class="block text-grey-dark mb-1" href="{{ route('user.feedback') }}">Feedback</a>
        <a class="block text-grey-dark mb-1" href="https://github.com/SjorsO/meel" target="_blank">Github</a>
        <a class="block text-grey-dark mb-1" href="{{ route('help') }}">Help</a>
    </div>
@endauth
