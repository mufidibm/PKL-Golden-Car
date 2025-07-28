<?php

namespace App\Filament\Resources\JasaResource\Pages;

use App\Filament\Resources\JasaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJasa extends EditRecord
{
    protected static string $resource = JasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Jasa')
                ->modalDescription('Apakah Anda yakin ingin menghapus jasa ini?')
                ->modalSubmitActionLabel('Ya, Hapus'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Edit Jasa';
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Jasa berhasil diperbarui';
    }
}