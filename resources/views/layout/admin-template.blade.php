@extends('layout.empty-template', [
    'title' => $title,
])

@section('body')
<body class="bg-grey-lightest min-h-full relative">

    <div id="app" class="container mx-auto px-4 py-2">

        <div class="flex justify-between items-center max-w-md mx-auto pt-2">

            <span class="flex items-center">
                <a class="text-black inline-block w-6 h-6" title="Meel.me" href="/">@include('helpers.svg.logo')</a>

                <h1 class="text-xl sm:text-3xl sm:ml-4 ml-2 pt-1">{{ $title }}</h1>
            </span>

            <span class="flex justify-between items-center">
                <a class="mr-4" href="{{ route('admin.inputLogs.index') }}">Written input logs</a>
            </span>
        </div>

        @yield('content')
    </div>

    <script type="text/javascript" src="{{ mix('js/scripts.js') }}"></script>

    @stack('footer')
</body>
@endsection
