<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

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
        // Register custom Blade directive for safe date formatting
        Blade::directive('dateFormat', function ($expression) {
            return "<?php echo app('date.formatter')->format($expression); ?>";
        });

        // Register date formatter singleton
        $this->app->singleton('date.formatter', function () {
            return new class {
                public function format($date, $format = 'd/m/Y')
                {
                    if (!$date) {
                        return '-';
                    }
                    
                    if (is_string($date)) {
                        try {
                            return Carbon::parse($date)->format($format);
                        } catch (\Exception $e) {
                            return '-';
                        }
                    }
                    
                    if ($date instanceof Carbon || $date instanceof \DateTime) {
                        return $date->format($format);
                    }
                    
                    return '-';
                }
            };
        });
    }
}
