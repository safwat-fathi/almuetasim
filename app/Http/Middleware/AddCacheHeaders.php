<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add cache headers to GET requests for static assets and non-sensitive pages
        if ($request->isMethod('get')) {
            $routeCacheConfigs = config('cache-headers.route_cache_configs');
            $defaultCacheTime = config('cache-headers.default_cache_time', 900);

            $cacheConfig = null;

            // Check each route pattern to see if it matches
            foreach ($routeCacheConfigs as $config) {
                foreach ($config['pattern'] as $pattern) {
                    if ($request->is($pattern)) {
                        $cacheConfig = $config;
                        break 2; // Break outer loop too
                    }
                }
            }

            if ($cacheConfig) {
                $response->headers->set('Cache-Control', $cacheConfig['directive']);
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $cacheConfig['max_age']) . ' GMT');
            } else {
                // Default cache for 15 minutes
                $response->headers->set('Cache-Control', "public, max-age={$defaultCacheTime}");
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $defaultCacheTime) . ' GMT');
            }
        }

        return $response;
    }
}