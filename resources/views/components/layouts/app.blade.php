<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Collect page SEO values from view sections (if present) and fall back to config/service --}}
    @php
      $pageTitle = trim(View::getSection('title') ?? '');
      $pageDescription = trim(View::getSection('description') ?? '');
      $initial = [];
      if (!empty($pageTitle)) {
        $initial['title'] = $pageTitle;
      }
      if (!empty($pageDescription)) {
        $initial['description'] = $pageDescription;
      }
    @endphp

    {{-- Render SEO meta (fills title, description, OG/Twitter, canonical) --}}
    <x-seo :data="$initial" />


    <!-- Vite styles -->
    @vite(['resources/css/app.css'])

    <!-- Google Fonts - optimise delivery -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- load non-blocking then swap in; provide noscript fallback for old browsers --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
      <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    </noscript>

    {{-- allow pages to request a hero/preload image for better LCP handling --}}
    @if(View::hasSection('preload_image') && $preload = trim(View::getSection('preload_image')))
      <link rel="preload" as="image" href="{{ e($preload) }}">
    @endif
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
