<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumResource\Pages;
use App\Filament\Resources\ForumResource\RelationManagers;
use App\Models\Forum;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;

class ForumResource extends Resource
{
    protected static ?string $model = Forum::class;

    protected static ?string $navigationGroup = 'Forums';

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')->relationship('user', 'name')->required(),
                BelongsToSelect::make('category_id')->relationship('category', 'name')->required(),
                TextInput::make('title')->required(),
                FileUpload::make('img')->image()->directory('uploads/forums')
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) ('Forum_'.uniqid().'_'. $file->getClientOriginalName());
                }),
                Textarea::make('body')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('category.name')->sortable()->searchable(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('body')->sortable()->searchable(),
                ImageColumn::make('img'),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (Forum $record)=>$record->delete())
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
            'index' => Pages\ListForums::route('/'),
            'create' => Pages\CreateForum::route('/create'),
            'edit' => Pages\EditForum::route('/{record}/edit'),
        ];
    }    
}
