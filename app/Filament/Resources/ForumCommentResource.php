<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumCommentResource\Pages;
use App\Filament\Resources\ForumCommentResource\RelationManagers;
use App\Models\ForumComment;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ForumCommentResource extends Resource
{
    protected static ?string $model = ForumComment::class;

    protected static ?string $navigationGroup = 'Forums';

    protected static ?string $navigationIcon = 'heroicon-o-annotation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')->relationship('user', 'name')->required(),
                BelongsToSelect::make('forum_id')->relationship('forum', 'title')->required(),
                TextInput::make('comment')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('forum.title')->sortable()->searchable(),
                TextColumn::make('comment')->sortable()->searchable(),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (ForumComment $record)=>$record->delete())
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
            'index' => Pages\ListForumComments::route('/'),
            'create' => Pages\CreateForumComment::route('/create'),
            'edit' => Pages\EditForumComment::route('/{record}/edit'),
        ];
    }    
}
