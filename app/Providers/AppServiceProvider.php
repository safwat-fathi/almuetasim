<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('money', function ($expression) {
            $php  = "<?php\n";
            $php .= "\$__val = (float) (".$expression.");\n";
            $php .= "if (!isset(\$GLOBALS['__egpFmt'])) {\n";
            $php .= "    \$GLOBALS['__egpFmt'] = class_exists('NumberFormatter') ? new \\NumberFormatter('ar_EG', \\NumberFormatter::CURRENCY) : null;\n";
            $php .= "}\n";
            $php .= "\$__fmt = \$GLOBALS['__egpFmt'];\n";
            $php .= "echo \$__fmt ? \$__fmt->formatCurrency(\$__val, 'EGP') : (number_format(\$__val, 2) . ' ج.م');\n";
            $php .= "?>";
            return $php;
        });

        // Share commonly accessed data with specific views only (best practice: avoid View::composer('*'))
        // This is more efficient as it only runs for views that actually need this data
        View::composer([
            'components.layouts.app',
            'components.layouts.partials.app.navbar',
            'components.layouts.partials.app.footer',
        ], function ($view) {
            // Cache categories for 60 minutes with only essential fields
            $categories = Cache::remember('categories_optimized', 3600, function () {
                return Category::select(['id', 'name', 'slug', 'description'])->get();
            });

            // Cache settings for 60 minutes
            $settings = Cache::remember('settings_global', 3600, function () {
                return [
                    'store_name' => 'Almuetasim',
                    'seo_description' => config('app.seo_description', 'متجر المعتصم لفلاتر ومحطات تنقية المياه'),
                    'seo_keywords' => config('app.seo_keywords', 'فلاتر مياه, محطات مياه, تنقية مياه, المعتصم'),
                    'seo_image' => config('app.seo_image', '/images/ALMUETASIM-300x212.png'),
                ];
            });

            $view->with('categories', $categories);
            $view->with('settings', $settings);
        });
    }
}
