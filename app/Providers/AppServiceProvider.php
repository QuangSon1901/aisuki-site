<?php

namespace App\Providers;

use App\Http\Helpers\LanguageHelper;
use App\Http\Helpers\SettingHelper;
use App\Services\MailService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (glob(app_path('Helpers') . '/*.php') as $file) {
            require_once $file;
        }

        $this->app->singleton(MailService::class, function ($app) {
            return new MailService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }
        
        View::composer('*', function ($view) {
            $view->with('activeLanguages', get_languages());
            $view->with('defaultLanguage', default_language());
            $view->with('generalSettings', settings_group('general'));
            $view->with('socialSettings', settings_group('social'));
        });
    }
}
