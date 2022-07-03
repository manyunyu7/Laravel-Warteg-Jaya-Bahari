<?php

namespace App\Filament\Resources\ProductInformationResource\Pages;

use App\Filament\Resources\ProductInformationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductInformation extends EditRecord
{
    protected static string $resource = ProductInformationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
