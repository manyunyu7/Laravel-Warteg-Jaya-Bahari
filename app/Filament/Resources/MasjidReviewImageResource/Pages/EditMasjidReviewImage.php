<?php

namespace App\Filament\Resources\MasjidReviewImageResource\Pages;

use App\Filament\Resources\MasjidReviewImageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMasjidReviewImage extends EditRecord
{
    protected static string $resource = MasjidReviewImageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
