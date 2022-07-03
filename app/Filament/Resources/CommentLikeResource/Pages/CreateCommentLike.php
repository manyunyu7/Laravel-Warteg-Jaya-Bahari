<?php

namespace App\Filament\Resources\CommentLikeResource\Pages;

use App\Filament\Resources\CommentLikeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCommentLike extends CreateRecord
{
    protected static string $resource = CommentLikeResource::class;
}
