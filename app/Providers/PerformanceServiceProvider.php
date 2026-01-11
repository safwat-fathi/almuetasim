<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enable query logging in debug mode to identify N+1 issues
        if (config('app.debug')) {
            DB::listen(function ($query) {
                // Log queries that take longer than 500ms
                if ($query->time > 500) {
                    Log::warning('Slow query detected', [
                        'query' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms',
                    ]);
                }
            });
        }

        // Check for important database indexes on application boot
        $this->checkDatabaseIndexes();
    }

    /**
     * Check if important database indexes exist for performance
     */
    private function checkDatabaseIndexes(): void
    {
        // Check if indexes exist for frequently queried columns
        $this->ensureIndexExists('products', 'slug');
        $this->ensureIndexExists('products', 'category_id');
        $this->ensureIndexExists('products', 'price');
        $this->ensureIndexExists('categories', 'slug');
    }

    /**
     * Ensure a database index exists on a table column
     */
    private function ensureIndexExists(string $table, string $column): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        // This is a check method - in a real implementation you might want to create the index
        // For now, we're just logging if an important index doesn't exist
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        $hasIndex = collect($indexes)->contains(function ($index) use ($column) {
            return $index->Column_name === $column;
        });

        if (!$hasIndex) {
            Log::warning("Performance: Missing database index on {$table}.{$column}");
        }
    }
}