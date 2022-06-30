<?php

namespace App\Filament\Resources\ProductInformationResource\Pages;

use App\Filament\Resources\ProductInformationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductInformation extends CreateRecord
{
    protected static string $resource = ProductInformationResource::class;
}
