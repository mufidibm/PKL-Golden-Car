<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Filament\Support\Facades\FilamentView;
use Filament\Navigation\NavigationItem;

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
        $setting = Setting::first();

        View::share('setting', $setting);

        FilamentView::registerRenderHook(
            'panels::topbar.start',
            fn(): string => view('filament.resources.components.topbar-logo')->render()
        );
    }
}
