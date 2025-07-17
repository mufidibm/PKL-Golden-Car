<?php

namespace App\Filament\Resources\PengerjaanSparepartResource\Pages;

use App\Filament\Resources\PengerjaanSparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengerjaanSparepart extends EditRecord
{
    protected static string $resource = PengerjaanSparepartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
