<?php

namespace App\Filament\Resources\MasjidResource\Pages;

use App\Filament\Resources\MasjidResource;
use App\Models\Masjid;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMasjid extends EditRecord
{
    protected static string $resource = MasjidResource::class;

    // protected function beforeSave(): void
    // {
    //     dd($this->form->getState());
    //     $img = $this->form->getState()['img'];

    //     unlink(public_path('storage/'. $img));

    // }

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

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     return unlink(public_path('storage/'.$data['img']));
    // }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
