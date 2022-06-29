<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasjidReviewImageResource\Pages;
use App\Filament\Resources\MasjidReviewImageResource\RelationManagers;
use App\Models\MasjidReviewImage;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;

class MasjidReviewImageResource extends Resource
{
    protected static ?string $model = MasjidReviewImage::class;

    protected static ?string $navigationGroup = 'Prayer';

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('masjidReview.name')->sortable()->searchable(),
                TextColumn::make('path'),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
           
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasjidReviewImages::route('/'),
            'create' => Pages\CreateMasjidReviewImage::route('/create'),
            'edit' => Pages\EditMasjidReviewImage::route('/{record}/edit'),
        ];
    }    
}
