<?php

namespace App\Filament\Resources\MasjidReviewResource\Pages;

use App\Filament\Resources\MasjidReviewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasjidReview extends EditRecord
{
    protected static string $resource = MasjidReviewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
