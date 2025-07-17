<?php

namespace App\Filament\Resources\KendaraanResource\Pages;

use App\Filament\Resources\KendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKendaraans extends ListRecords
{
    protected static string $resource = KendaraanResource::class;

    protected function getHeaderActions(): array
    {
       return [
        Actions\CreateAction::make()
                ->modalHeading('Tambah Kendaraan')
                ->modalSubmitActionLabel('Simpan')
                ->modalWidth('7xl')
                ->label('Tambah') // 
    ];
    }
}
