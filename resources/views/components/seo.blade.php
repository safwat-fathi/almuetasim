@props(['data' => []])

@php
    // Merge passed data with computed defaults from the SeoManager
    $seoManager = app(\App\Services\SeoManager::class);
    $computed = $seoManager->for($data ?: null);
    $title = $computed['title'] ?? config('seo.default_title');
    $description = $computed['description'] ?? config('seo.default_description');
    $image = $computed['image'] ?? url(config('seo.default_social_image'));
    $canonical = $computed['canonical'] ?? url()->current();
    $type = $computed['type'] ?? 'website';
@endphp

<title>{{ e($title) }}</title>
<meta name="description" content="{{ e($description) }}" />
<link rel="canonical" href="{{ e($canonical) }}" />

<!-- Open Graph -->
<meta property="og:title" content="{{ e($title) }}" />
<meta property="og:description" content="{{ e($description) }}" />
<meta property="og:image" content="{{ e($image) }}" />
<meta property="og:type" content="{{ e($type) }}" />
<meta property="og:url" content="{{ e($canonical) }}" />

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="{{ e(config('seo.twitter_handle')) }}" />
<meta name="twitter:title" content="{{ e($title) }}" />
<meta name="twitter:description" content="{{ e($description) }}" />
<meta name="twitter:image" content="{{ e($image) }}" />

@if(!empty($data['json_ld'] ?? false))
    <script type="application/ld+json">{!! json_encode($data['json_ld'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endif
