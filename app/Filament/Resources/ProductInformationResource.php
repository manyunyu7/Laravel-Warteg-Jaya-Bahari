<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductInformationResource\Pages;
use App\Filament\Resources\ProductInformationResource\RelationManagers;
use App\Models\ProductInformation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductInformationResource extends Resource
{
    protected static ?string $model = ProductInformation::class;

    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('product_id')->relationship('product', 'name')->required(),
                TagsInput::make('allergy')->required(),
                TagsInput::make('environment')->required(),
                TagsInput::make('ingredient')->required(),
                TagsInput::make('summary')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->sortable()->searchable(),
                TagsColumn::make('allergy')->searchable()->sortable(),
                TagsColumn::make('environment')->searchable()->sortable(),
                TagsColumn::make('ingredient')->searchable()->sortable(),
                TagsColumn::make('summary')->searchable()->sortable(),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (ProductInformation $record)=>$record->delete())
                ->requiresConfirmation()
                ->icon('heroicon-o-trash')
                ->color('danger')
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
            'index' => Pages\ListProductInformation::route('/'),
            'edit' => Pages\EditProductInformation::route('/{record}/edit'),
        ];
    }    
}
