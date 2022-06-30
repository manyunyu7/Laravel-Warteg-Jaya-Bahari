<?php

namespace App\Filament\Resources\ProductInformationResource\Pages;

use App\Filament\Resources\ProductInformationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductInformation extends ListRecords
{
    protected static string $resource = ProductInformationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
