<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache Headers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the cache headers for different routes and resource types.
    | This helps improve performance without affecting SEO by caching static assets.
    |
    */

    'default_cache_time' => 900, // 15 minutes

    'route_cache_configs' => [
        // Static content - cache longer
        'assets' => [
            'pattern' => ['*.css', '*.js', '*.png', '*.jpg', '*.jpeg', '*.gif', '*.svg', '*.webp'],
            'max_age' => 31536000, // 1 year
            'directive' => 'public, max-age=31536000, immutable'
        ],
        
        // Homepage - cache for 1 hour
        'homepage' => [
            'pattern' => ['/'],
            'max_age' => 3600, // 1 hour
            'directive' => 'public, max-age=3600'
        ],
        
        // Product and category pages - cache for 30 minutes
        'product_category_pages' => [
            'pattern' => ['product/*', 'category/*'],
            'max_age' => 1800, // 30 minutes
            'directive' => 'public, max-age=1800'
        ],
        
        // API routes - cache for shorter time
        'api' => [
            'pattern' => ['api/*'],
            'max_age' => 300, // 5 minutes
            'directive' => 'public, max-age=300'
        ],
    ],
];