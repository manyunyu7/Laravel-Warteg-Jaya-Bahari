<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TableUsers extends BaseWidget
{
    protected ?string $maxContentWidth = 'full';
    
    protected function getTableQuery(): Builder
    {
        return User::query()->latest()->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('id')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('roles_id')->sortable()->searchable(),
            Tables\Columns\ImageColumn::make('photo')->rounded(),
            Tables\Columns\TextColumn::make('phone_number')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('email_verified_at')->searchable()->sortable(),
        ];
    }
}
