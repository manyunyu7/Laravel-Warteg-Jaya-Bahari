<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasjidResource\Pages;
use App\Filament\Resources\MasjidResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use App\Models\Masjid;
use Filament\Resources\str;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;

class MasjidResource extends Resource
{
    protected static ?string $model = Masjid::class;

    protected static ?string $navigationGroup = 'Prayers';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('type_id')
                    ->options([
                        '1' => 'University',
                        '2' => 'Mosque',
                        '3' => 'Hotel',
                        '4' => 'Tourist Attraction',
                        '5' => 'Hospital',
                        '6' => 'International Airport',
                        '7' => 'Restaurant',
                    ]),
                TextInput::make('phone')->required(),
                TextInput::make('operating_start'),
                TextInput::make('operating_end'),
                TextInput::make('address')->required(),
                TextInput::make('lat')->required(),
                TextInput::make('long')->required(),
                Textarea::make('facilities')->required(),
                FileUpload::make('img')->image()->directory('uploads/masjids')
                    ->panelAspectRatio('4:1')->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) ('Masjid_'.uniqid().'_'. $file->getClientOriginalName());
                    })
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('type_id')->searchable()->sortable(),
                TextColumn::make('facilities')->searchable()->sortable(),
                TextColumn::make('phone')->searchable()->sortable(),
                TextColumn::make('operating_start')->searchable()->sortable(),
                TextColumn::make('operating_end')->searchable()->sortable(),
                TextColumn::make('address')->searchable()->sortable(),
                TextColumn::make('lat')->searchable()->sortable(),
                TextColumn::make('long')->searchable()->sortable(),
                ImageColumn::make('img'),
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
            'index' => Pages\ListMasjids::route('/'),
            'edit' => Pages\EditMasjid::route('/{record}/edit'),
        ];
    }    
}
