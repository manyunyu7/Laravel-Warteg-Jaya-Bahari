<?php

namespace App\Filament\Resources\MasjidResource\Pages;

use App\Filament\Resources\MasjidResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasjid extends EditRecord
{
    protected static string $resource = MasjidResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
