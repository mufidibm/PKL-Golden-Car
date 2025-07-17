<?php

namespace App\Filament\Resources\PengerjaanServisResource\Pages;

use App\Filament\Resources\PengerjaanServisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengerjaanServis extends EditRecord
{
    protected static string $resource = PengerjaanServisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
