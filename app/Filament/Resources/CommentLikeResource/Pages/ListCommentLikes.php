<?php

namespace App\Filament\Resources\CommentLikeResource\Pages;

use App\Filament\Resources\CommentLikeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommentLikes extends ListRecords
{
    protected static string $resource = CommentLikeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
