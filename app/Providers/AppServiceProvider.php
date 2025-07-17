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
            fn(): string => '
        <div class="flex items-center" style="margin-left: 230px; margin-right: 20px; margin-top: 25px; scale: 1.5;">
            <img 
                src="' . asset('images/logo.png') . '" 
                alt="Logo" 
                class="h-16 w-auto"
                style="max-height: 56px; min-height: 130px;"
            >
        </div>
    '
        );
    }
}
