@auth()
    @include('layout.header-auth', ['title' => $title])
@endauth

@guest
    @include('layout.header-guest', ['title' => $title])
@endguest
