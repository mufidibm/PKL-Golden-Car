<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    // ✅ Notifikasi sukses setelah berhasil create
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Customer berhasil ditambahkan!')
            ->success()
            ->duration(3000) // dalam milidetik
            ->send();
    }

    // ✅ Redirect ke halaman list setelah Create
    protected function getRedirectUrl(): string
    {
        return CustomerResource::getUrl();
    }

    // ✅ (Opsional) Tambah tombol atau aksi lain
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
