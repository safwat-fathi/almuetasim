@php
    use Illuminate\Support\Str;
@endphp

@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'robots' => null,
    'metaImage' => null,
    'ogType' => null,
    'twitterCard' => null,
])

@php
    $settingsData = isset($settings) && is_array($settings) ? $settings : [];
    $storeName = $settingsData['store_name'] ?? 'Almuetasim';
    $rawPageTitle = $title ?? trim($__env->yieldContent('title'));
    $computedTitle = $rawPageTitle ? "{$storeName} - {$rawPageTitle}" : $storeName;
    $rawDescription = $description ?? trim($__env->yieldContent('description')) ?: ($settingsData['seo_description'] ?? 'متجر المعتصم لفلاتر ومحطات تنقية المياه');
    $metaDescription = Str::of($rawDescription)->stripTags()->squish()->limit(160, '')->value();
    $rawKeywords = $keywords ?? trim($__env->yieldContent('keywords')) ?: ($settingsData['seo_keywords'] ?? 'فلاتر مياه,محطات مياه,تنقية مياه,المعتصم');
    $metaKeywords = Str::of($rawKeywords)->trim()->value();
    $rawRobots = $robots ?? trim($__env->yieldContent('robots'));
    $robotsDirectives = $rawRobots ?: 'index,follow';
    $rawImage = $metaImage ?? trim($__env->yieldContent('meta_image')) ?: ($settingsData['seo_image'] ?? asset('images/ALMUETASIM-300x212.png'));
    $metaImageUrl = Str::startsWith($rawImage, ['http://', 'https://']) ? $rawImage : url($rawImage);
    $canonicalUrl = url()->current();
    $computedOgType = $ogType ?? trim($__env->yieldContent('og_type')) ?: 'website';
    $computedTwitterCard = $twitterCard ?? trim($__env->yieldContent('twitter_card')) ?: 'summary_large_image';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $computedTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="{{ $robotsDirectives }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $computedTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="{{ $computedOgType }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $metaImageUrl }}">
    <meta name="twitter:card" content="{{ $computedTwitterCard }}">
    <meta name="twitter:title" content="{{ $computedTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImageUrl }}">
    @yield('meta')
    @stack('meta')

    <!-- Resource hints for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="{{ asset('images/filter-no-bg.png') }}" as="image" type="image/png" />

    <!-- Vite styles -->
    @vite(['resources/css/app.css'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style" />
    <link rel="preload" href="{{ Vite::asset('resources/js/app.js') }}" as="script" />

    <!-- Accessibility improvements -->
    <style>
        /* Focus indicators for keyboard navigation */
        :focus {
            outline: 2px solid #2d3b61;
            outline-offset: 2px;
        }

        /* Skip to content link for screen readers */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #000;
            color: #fff;
            padding: 8px;
            text-decoration: none;
            transition: top 0.3s;
            z-index: 10000;
        }

        .skip-link:focus {
            top: 6px;
        }
    </style>
</head>

<body class="font-tajawal bg-base-100">
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-link"> التخطي إلى المحتوى الرئيسي</a>

    {{-- Navbar --}}
        <x-layouts.partials.app.navbar />

    <main class="min-h-screen" id="main-content">
        {{ $slot }}
    </main>

    {{-- Footer --}}
        <x-layouts.partials.app.footer />
    <!-- Vite scripts -->
    @vite('resources/js/app.js')
</body>


</html>
