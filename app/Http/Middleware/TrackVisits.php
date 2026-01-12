<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $date = now()->toDateString();

        // Use a cache key to prevent duplicate inserts for the same IP + Date
        $cacheKey = "visit:$ip:$date";

        // We check if we already tracked this IP today
        if (!Cache::has($cacheKey)) {
            // Track the visit in the database
            DB::table('visits')->insert([
                'ip_address' => $ip,
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'visited_at' => $date,
                'created_at' => now(),
            ]);

            // Set cache for 24 hours (or until end of day basically) to avoid re-querying/re-inserting
            Cache::put($cacheKey, true, now()->addHours(24));
        }

        return $next($request);
    }
}
