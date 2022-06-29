<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('roles_id')
                ->options([
                    '1' => 'Admin',
                    '2' => 'Customer',
                    '3' => 'Restoran',
                ]),
                FileUpload::make('img')->image()->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) ('public/uploads/' .'User_'.uniqid().'.'. $file->getClientOriginalExtension());
                    }),
                TextInput::make('email')->required(),
                DateTimePicker::make('email_verified_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('roles_id')->sortable()->searchable(),
                ImageColumn::make('photo')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('email_verified_at')->searchable()->sortable(),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (Masjid $record)=>$record->delete())
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
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
