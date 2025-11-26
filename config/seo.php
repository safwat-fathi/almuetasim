<?php

return [
    /* Site-wide defaults */
    'site_name' => env('APP_NAME', 'Almuetasim'),
    'default_title' => env('SEO_DEFAULT_TITLE', 'Almuetasim — Marketplace'),
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', 'Find great products and offers.'),
    'default_social_image' => env('SEO_DEFAULT_SOCIAL_IMAGE', '/build/images/social-default.webp'),

    /* Length constraints */
    'max_title' => 60,
    'max_description' => 160,

    /* Social defaults */
    'twitter_handle' => env('SEO_TWITTER_HANDLE', '@yourhandle'),
];
