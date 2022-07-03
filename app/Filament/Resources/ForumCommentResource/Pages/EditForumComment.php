<?php

namespace App\Filament\Resources\ForumCommentResource\Pages;

use App\Filament\Resources\ForumCommentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForumComment extends EditRecord
{
    protected static string $resource = ForumCommentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
