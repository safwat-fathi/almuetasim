<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('app.name'))</title>

    <!-- Vite styles -->
    @vite(['resources/css/app.css'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="font-tajawal min-h-screen bg-base-100">

    {{-- Navbar --}}
    @include('layouts.partials.admin.navbar')

    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            @yield('content')
        </div>

        @include('layouts.partials.admin.sidebar')
    </div>

    <!-- Vite scripts -->
    @vite('resources/js/app.js')
</body>

</html>
