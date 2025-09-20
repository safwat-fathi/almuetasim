<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'متجر مودرن شوب' }}</title>

    <!-- Vite styles -->
    @vite(['resources/css/app.css'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet"> 
</head>
<body class="font-tajawal min-h-screen bg-base-100">
	{{-- Navbar --}}
    @include('layouts.partials.navbar')

     <main class="min-h-screen">
        @yield('content')
    </main>

		{{-- Footer --}}
    @include('layouts.partials.footer')
    <!-- Vite scripts -->
    @vite('resources/js/app.js')
</body>
</html>
