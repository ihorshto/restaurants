<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased h-screen bg-gray-100">
        <div class=" flex flex-col sm:justify-center items-center">
            <div>
                <a href="/">
                    <img src="{{asset('images/logo.svg')}}" alt="" class="w-26 h-20">
                </a>
            </div>

            <div class="w-full mx-auto px-2.5 sm:px-6 lg:px-8 py-2.5 sm:py-6">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
