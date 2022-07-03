<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumLikeResource\Pages;
use App\Filament\Resources\ForumLikeResource\RelationManagers;
use App\Models\ForumLike;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ForumLikeResource extends Resource
{
    protected static ?string $model = ForumLike::class;

    protected static ?string $navigationGroup = 'Forums';

    protected static ?string $navigationIcon = 'heroicon-o-chevron-double-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')->relationship('user', 'name')->required(),
                BelongsToSelect::make('forum_id')->relationship('forum', 'title')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('forum.title')->sortable()->searchable(),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (ForumLike $record)=>$record->delete())
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
            'index' => Pages\ListForumLikes::route('/'),
            'create' => Pages\CreateForumLike::route('/create'),
            'edit' => Pages\EditForumLike::route('/{record}/edit'),
        ];
    }    
}
