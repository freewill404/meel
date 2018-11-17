<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="_5MZK0aUBG7BmZz2YTyqlYVMDjQpJ2I9yJvSzQE7Rz8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? '' }}" />

    <link rel="canonical" href="{{ URL::current() }}" />

    <title>{{ $title }}</title>

    @stack('head')

    <link rel="icon" type="image/png" href="/favicon.png" />

    <link href="https://fonts.googleapis.com/css?family=Assistant:400,600,700,800" rel="stylesheet">
    <style>
        body {
            font-family: 'Assistant', sans-serif;
        }
    </style>

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
@yield('body')
</html>
