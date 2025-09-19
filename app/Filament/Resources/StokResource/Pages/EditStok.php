<?php

namespace App\Filament\Resources\StokResource\Pages;

use App\Filament\Resources\StokResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStok extends EditRecord
{
    protected static string $resource = StokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Stok - ' . $this->record->nama_stok;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}