<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;

class FacultyDashboard extends Page
{
    protected ?string $heading = 'Dashboard';

    protected static string $view = 'filament.pages.faculty.dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $slug = '/faculty/dashboard';

    public static function getMiddlewares(): string | array
    {
        return static::$middlewares;
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteName()))
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->url(static::getNavigationUrl())
                ->visible(function () {
                    if (auth()->user() instanceof User) {
                        return false;
                    }
                    return true;
                }),
        ];
    }
}
