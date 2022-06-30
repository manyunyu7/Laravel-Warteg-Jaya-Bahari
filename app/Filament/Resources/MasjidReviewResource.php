<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasjidReviewResource\Pages;
use App\Filament\Resources\MasjidReviewResource\RelationManagers;
use App\Models\MasjidReview;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasjidReviewResource extends Resource
{
    protected static ?string $model = MasjidReview::class;

    protected static ?string $navigationGroup = 'Prayers';

    protected static ?string $navigationIcon = 'heroicon-o-annotation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('masjid_id')->relationship('masjid', 'name')->required(),
                BelongsToSelect::make('user_id')->relationship('user', 'name')->required(),
                BelongsToSelect::make('rating_id')->relationship('rating', 'name')->required(),
                Textarea::make('comment')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('masjid.name')->sortable()->searchable(),
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('rating.name')->sortable()->searchable(),
                TextColumn::make('comment')->sortable()->searchable(),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (MasjidReview $record)=>$record->delete())
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
            'index' => Pages\ListMasjidReviews::route('/'),
            'edit' => Pages\EditMasjidReview::route('/{record}/edit'),
        ];
    }    
}
