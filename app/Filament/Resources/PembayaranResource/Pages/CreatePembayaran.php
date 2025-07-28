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
        $detailItems = session('bayar_detail_items') ?? [];

        if (empty($detailItems)) {
            $this->notify('danger', 'Detail pembayaran kosong. Ulangi proses dari Transaksi.');
            throw new \Exception('Detail pembayaran kosong.');
        }

        // Pastikan nilai default
        $data['biaya_jasa'] = $data['biaya_jasa'] ?? 0;
        $data['kembalian'] = max(0, $data['dibayar'] - $data['total_bayar']);

        $record = static::getModel()::create($data);

        foreach ($detailItems as $item) {
            PembayaranDetail::create([
                'pembayaran_id' => $record->id,
                'jenis_item'    => $item['jenis_item'],
                'item_id'       => $item['item_id'],
                'nama_item'     => $item['nama_item'],
                'qty'           => $item['qty'],
                'harga_satuan'  => $item['harga_satuan'],
                'subtotal'      => $item['subtotal'],
            ]);

            if ($item['jenis_item'] === 'sparepart') {
                TransaksiMasukItem::create([
                    'transaksi_masuk_id' => $record->id_transaksi_masuk,
                    'barang_id'          => $item['item_id'],
                    'qty'                => $item['qty'],
                    'harga'              => $item['harga_satuan'],
                    'subtotal'           => $item['subtotal'],
                ]);
            }
        }

        // session()->forget(['bayar_transaksi_id', 'bayar_detail_items', 'bayar_total']);

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
