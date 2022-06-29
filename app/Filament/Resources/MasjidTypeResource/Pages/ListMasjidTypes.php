<?php

namespace App\Filament\Resources\MasjidTypeResource\Pages;

use App\Filament\Resources\MasjidTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasjidTypes extends ListRecords
{
    protected static string $resource = MasjidTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
