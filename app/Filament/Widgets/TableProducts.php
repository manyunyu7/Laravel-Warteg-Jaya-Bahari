<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TableProducts extends BaseWidget
{
    protected ?string $maxContentWidth = 'full';
    protected function getTableQuery(): Builder
    {
        return Product::query()->latest()->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('certification.name')->sortable()->searchable(),
            TextColumn::make('category.name')->sortable()->searchable(),
            TextColumn::make('code')->sortable()->searchable(),
            ImageColumn::make('img'),
        ];
    }
}
