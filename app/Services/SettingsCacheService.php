<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsCacheService
{
    /**
     * Cache key for settings
     */
    private const CACHE_KEY = 'site_settings';

    /**
     * Cache duration in seconds (1 hour)
     */
    private const CACHE_TTL = 3600;

    /**
     * Get all settings from cache or database
     *
     * @return array
     */
    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return DB::table('settings')->pluck('value', 'key')->all();
        });
    }

    /**
     * Get a specific setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->all();
        return $settings[$key] ?? $default;
    }

    /**
     * Invalidate the settings cache
     *
     * @return void
     */
    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Refresh the cache
     *
     * @return array
     */
    public function refresh(): array
    {
        $this->flush();
        return $this->all();
    }

    /**
     * Check if cache exists
     *
     * @return bool
     */
    public function isCached(): bool
    {
        return Cache::has(self::CACHE_KEY);
    }
}
