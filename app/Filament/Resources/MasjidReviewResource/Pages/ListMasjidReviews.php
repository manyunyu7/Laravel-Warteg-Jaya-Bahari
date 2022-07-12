<?php

namespace App\Filament\Resources\MasjidReviewResource\Pages;

use App\Filament\Resources\MasjidReviewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasjidReviews extends ListRecords
{
    protected static string $resource = MasjidReviewResource::class;
    
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
