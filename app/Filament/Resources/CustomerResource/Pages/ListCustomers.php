<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
       return [
        Actions\CreateAction::make()
                ->modalHeading('Tambah Customer')
                ->modalSubmitActionLabel('Simpan')
                ->modalWidth('7xl')
                ->label('Tambah') // 
    ];
    }
}
