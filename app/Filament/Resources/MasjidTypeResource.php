<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasjidTypeResource\Pages;
use App\Filament\Resources\MasjidTypeResource\RelationManagers;
use App\Models\MasjidType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasjidTypeResource extends Resource
{
    protected static ?string $model = MasjidType::class;

    protected static ?string $navigationGroup = 'Prayers';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
            ])->prependActions([
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasjidTypes::route('/'),
            'create' => Pages\CreateMasjidType::route('/create'),
            'edit' => Pages\EditMasjidType::route('/{record}/edit'),
        ];
    }    
}
