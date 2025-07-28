<?php

namespace App\Filament\Resources\JasaResource\Pages;

use App\Filament\Resources\JasaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJasa extends CreateRecord
{
    protected static string $resource = JasaResource::class;
    
    public function getTitle(): string
    {
        return 'Tambah Jasa Baru';
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Jasa berhasil ditambahkan';
    }
}

