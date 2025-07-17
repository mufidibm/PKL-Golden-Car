<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use App\Filament\Resources\TransaksiMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\PembayaranDetail;
use App\Models\TransaksiMasukItem;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = static::getModel()::create($data);

        $detailItems = session('bayar_detail_items') ?? [];

        foreach ($detailItems as $item) {
            // Simpan ke pembayaran detail
            PembayaranDetail::create([
                'pembayaran_id' => $record->id,
                'item_id'       => $item['item_id'],
                'nama_item'     => $item['nama_item'],
                'qty'           => $item['qty'],
                'harga_satuan'  => $item['harga_satuan'],
                'subtotal'      => $item['subtotal'],
            ]);

            // Simpan juga ke transaksi_masuk_items
            TransaksiMasukItem::create([
                'transaksi_masuk_id' => $record->id_transaksi_masuk,
                'barang_id'          => $item['item_id'],
                'qty'                => $item['qty'],
                'harga'              => $item['harga_satuan'],
                'subtotal'           => $item['subtotal'],
            ]);
        }

        // Bersihkan session
        session()->forget(['bayar_transaksi_id', 'bayar_detail_items', 'bayar_total']);

        // Tampilkan invoice
        $this->js("window.open('/invoice/{$record->id}', '_blank');");

        return $record;
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make('create')
                ->label('Simpan Pembayaran')
                ->submit('create')
                ->extraAttributes([
                    'class' => 'w-full'
                ])
        ];
    }

    protected function getCreatedRedirectUrl(): string
    {
        // Redirect ke edit transaksi masuk
        return TransaksiMasukResource::getUrl('edit', [
            'record' => $this->record->id_transaksi_masuk
        ]);
    }
}
