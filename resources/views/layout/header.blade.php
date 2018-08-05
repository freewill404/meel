@auth()
    @include('layout.header-auth', ['title' => $title, 'maxWidth' => $maxWidth])
@endauth

@guest
    @include('layout.header-guest', ['title' => $title])
@endguest
