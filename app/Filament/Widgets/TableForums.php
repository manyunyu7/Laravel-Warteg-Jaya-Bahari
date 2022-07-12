<?php

namespace App\Filament\Widgets;

use App\Models\Forum;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TableForums extends BaseWidget
{
    // protected ?string $columnSpan = 'full';
    protected function getTableQuery(): Builder
    {
        return Forum::query()->latest()->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user.name')->sortable()->searchable(),
            TextColumn::make('category.name')->sortable()->searchable(),
            TextColumn::make('title')->sortable()->searchable(),
            TextColumn::make('body')->sortable()->searchable(),
            ImageColumn::make('img'),
        ];
    }
}
