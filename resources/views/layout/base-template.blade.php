<!doctype html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="_5MZK0aUBG7BmZz2YTyqlYVMDjQpJ2I9yJvSzQE7Rz8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? '' }}" />

    <link rel="canonical" href="{{ URL::current() }}" />

    <title>{{ $title }}</title>

    @stack('head')

    <link rel="icon" type="image/png" href="/favicon.png" />

    <link rel="stylesheet" type="text/css" href="{{ mix('css/main.css') }}" />

    @if(App::environment('production'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-85344990-5"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-85344990-5');
        </script>
    @endif

</head>
<body class="bg-grey-lighter min-h-full relative">

    <div id="app" class="container mx-auto p-4" style="min-height:500px;">
        @yield('content')
    </div>

    @include('layout.footer')

    <script type="text/javascript" src="{{ mix('js/scripts.js') }}"></script>

    @stack('footer')

</body>
</html>
