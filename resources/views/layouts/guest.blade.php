<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- PWA Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#006b3f">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <!-- Favicons -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/psa_accommodations_16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/psa_accommodations_32x32.png') }}">
        <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon/psa_accommodations_48x48.png') }}">
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon/psa_accommodations_64x64.png') }}">
        <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('mobile/psa_accommodations_192x192.png') }}">
        <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('mobile/psa_accommodations_512x512.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-4">
                <a href="/" class="flex flex-col items-center">
                    <img src="{{ asset('mobile/psa_accommodations_192x192.png') }}" alt="Priority Accommodations Logo" class="w-20 h-20 mb-2">
                    <span class="text-2xl font-bold text-gray-800">Priority Accommodations</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
