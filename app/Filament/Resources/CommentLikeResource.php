<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentLikeResource\Pages;
use App\Filament\Resources\CommentLikeResource\RelationManagers;
use App\Models\CommentLike;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\MultiSelect;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentLikeResource extends Resource
{
    protected static ?string $model = CommentLike::class;

    protected static ?string $navigationGroup = 'Forums';

    protected static ?string $navigationIcon = 'heroicon-o-chevron-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')->relationship('user', 'name')->required(),
                MultiSelect::make('comment_id')->relationship('comment', 'id')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('comment.comment')->sortable()->searchable(),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (CommentLike $record)=>$record->delete())
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
            'index' => Pages\ListCommentLikes::route('/'),
            'create' => Pages\CreateCommentLike::route('/create'),
            'edit' => Pages\EditCommentLike::route('/{record}/edit'),
        ];
    }    
}
