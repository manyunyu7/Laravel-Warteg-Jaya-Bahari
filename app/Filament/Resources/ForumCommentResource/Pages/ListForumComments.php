<?php

namespace App\Filament\Resources\ForumCommentResource\Pages;

use App\Filament\Resources\ForumCommentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForumComments extends ListRecords
{
    protected static string $resource = ForumCommentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
