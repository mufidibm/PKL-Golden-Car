<?php

namespace App\Filament\Resources\PengerjaanServisResource\Pages;

use App\Filament\Resources\PengerjaanServisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengerjaanServis extends ListRecords
{
    protected static string $resource = PengerjaanServisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
