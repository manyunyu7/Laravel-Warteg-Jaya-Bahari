<?php

namespace App\Filament\Widgets;

use App\Models\Masjid;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TableMasjids extends BaseWidget
{
    protected ?string $maxContentWidth = 'full';
    protected function getTableQuery(): Builder
    {
        return Masjid::query()->latest()->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('type_id')->searchable()->sortable(),
                TextColumn::make('facilities')->searchable()->sortable(),
                TextColumn::make('phone')->searchable()->sortable(),
                TextColumn::make('operating_start')->searchable()->sortable(),
                TextColumn::make('operating_end')->searchable()->sortable(),
                TextColumn::make('address')->searchable()->sortable(),
                TextColumn::make('lat')->searchable()->sortable(),
                TextColumn::make('long')->searchable()->sortable(),
                ImageColumn::make('img'),
        ];
    }
}
