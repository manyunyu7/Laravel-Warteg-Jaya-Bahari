<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
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
                FileUpload::make('photo')->image()->directory('uploads/users')
                    ->panelAspectRatio('4:1')->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) ('User_'.uniqid().'_'. $file->getClientOriginalName());
                    }),
                Select::make('roles_id')
                ->options([
                    '1' => 'Admin',
                    '2' => 'Customer',
                ]),
                TextInput::make('phone_number')->required(),
                TextInput::make('email')->required(),
                TextInput::make('password')->password()->dehydrateStateUsing(fn ($state) => Hash::make($state))->required(),
                TextInput::make('passwordConfirmation')->password()->same('password')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('roles_id')->sortable()->searchable(),
                ImageColumn::make('photo')->rounded(),
                TextColumn::make('phone_number')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('email_verified_at')->searchable()->sortable(),
            ])->prependActions([
                LinkAction::make('delete')
                ->action(fn (User $record)=>$record->delete())
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
