<?php

namespace App\Providers;

use App\Http\Helpers\LanguageHelper;
use App\Http\Helpers\SettingHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('activeLanguages', get_languages());
            $view->with('defaultLanguage', default_language());
            $view->with('generalSettings', settings_group('general'));
            $view->with('socialSettings', settings_group('social'));
        });
    }
}
