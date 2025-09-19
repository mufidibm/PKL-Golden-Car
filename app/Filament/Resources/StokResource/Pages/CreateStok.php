<?php

namespace App\Filament\Resources\StokResource\Pages;

use App\Filament\Resources\StokResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStok extends CreateRecord
{
    protected static string $resource = StokResource::class;

    public function getTitle(): string
    {
        return 'Tambah Stok Baru';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}