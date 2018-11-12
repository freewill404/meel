@extends('layout.empty-template', [
    'title' => $title,
    'description' => $description ?? '',
])

@section('body')
<body class="bg-grey-lightest min-h-full relative">

    <div id="app" class="container mx-auto px-4 py-2" style="min-height:500px;">
        @yield('content')
    </div>

    @include('layout.footer')

    <script type="text/javascript" src="{{ mix('js/scripts.js') }}"></script>

    @stack('footer')
</body>
@endsection
