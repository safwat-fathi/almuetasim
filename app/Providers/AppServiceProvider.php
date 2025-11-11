<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
    }
}
