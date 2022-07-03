<?php

namespace App\Filament\Widgets;

use App\Models\Forum;
use App\Models\Masjid;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Users', User::where('roles_id',2)->count())
                ->description('Total Customer')
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make('Masjids', Masjid::count())
                ->description('Total Masjid')
                ->descriptionIcon('heroicon-s-home')
                ->color('success'),
            Card::make('Products', Product::count())
                ->description('Total Product')
                ->descriptionIcon('heroicon-s-cube')
                ->color('success'),
            Card::make('Forums', Forum::count())
                ->description('Total Forum')
                ->descriptionIcon('heroicon-s-chat-alt-2')
                ->color('success'),
        ];
    }
}
