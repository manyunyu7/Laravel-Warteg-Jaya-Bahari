<?php

namespace App\Filament\Resources\ForumResource\Pages;

use App\Filament\Resources\ForumResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditForum extends EditRecord
{
    protected static string $resource = ForumResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {  
        if (isset($this->form->getState()['img'])) {
            $path = public_path('storage/'. $record->img);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $record->update($data);

        return $record;
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
