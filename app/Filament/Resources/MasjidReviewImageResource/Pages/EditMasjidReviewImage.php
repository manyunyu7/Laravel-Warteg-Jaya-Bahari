<?php

namespace App\Filament\Resources\MasjidReviewImageResource\Pages;

use App\Filament\Resources\MasjidReviewImageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

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
