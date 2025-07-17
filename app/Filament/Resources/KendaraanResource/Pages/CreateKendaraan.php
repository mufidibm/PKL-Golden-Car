<?php

namespace App\Filament\Resources\KendaraanResource\Pages;

use App\Filament\Resources\KendaraanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateKendaraan extends CreateRecord
{
    protected static string $resource = KendaraanResource::class;

    // ✅ Notifikasi sukses setelah berhasil create
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Kendaraan berhasil ditambahkan!')
            ->success()
            ->duration(3000) // dalam milidetik
            ->send();
    }

    // ✅ Redirect ke halaman list setelah Create
    protected function getRedirectUrl(): string
    {
        return KendaraanResource::getUrl();
    }

    // ✅ (Opsional) Tambah tombol atau aksi lain
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
