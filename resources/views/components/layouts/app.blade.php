<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.name'))</title>


    <!-- Vite styles -->
    @vite(['resources/css/app.css'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="font-tajawal bg-base-100">
    {{-- Navbar --}}
		<x-layouts.partials.app.navbar />

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    {{-- Footer --}}
		<x-layouts.partials.app.footer />
    <!-- Vite scripts -->
    @vite('resources/js/app.js')
</body>

</html>
