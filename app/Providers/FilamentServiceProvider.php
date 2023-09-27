<?php

namespace App\Providers;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\UserMenuItem;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');

            // Filament::registerNavigationItems([
            //     NavigationItem::make('Dashboard')
            //         ->url('/admin/faculty/dashboard')
            //         ->icon('heroicon-o-presentation-chart-line')
            //         ->activeIcon('heroicon-s-presentation-chart-line')
            //         ->visible(function () {
            //             if (auth()->user() instanceof User) {
            //                 return true;
            //             }
            //         }),
            // ]);
        });
    }
}
