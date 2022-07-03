<?php

namespace App\Filament\Resources\MasjidTypeResource\Pages;

use App\Filament\Resources\MasjidTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasjidType extends EditRecord
{
    protected static string $resource = MasjidTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
