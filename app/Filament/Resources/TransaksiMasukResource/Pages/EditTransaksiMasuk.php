<?php

namespace App\Filament\Resources\TransaksiMasukResource\Pages;

use App\Filament\Resources\TransaksiMasukResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Models\PengerjaanServis;
use Filament\Notifications\Notification;
use Illuminate\View\View;


class EditTransaksiMasuk extends EditRecord
{
    protected static string $resource = TransaksiMasukResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakEstimasi')
                ->label('Cetak Estimasi')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('transaksi.estimasi', $this->record->id))
                ->openUrlInNewTab(),
            // Aksi default Filament
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    public function getFooter(): View
    {
        $record = $this->record;

        $pengerjaanList = $record->pengerjaanServis()
            ->with(['spareparts.barang', 'pegawai', 'jasas.jasa'])
            ->get();
            

        return view('filament.resources.transaksi-masuk.custom-pengerjaan', [
            'pengerjaanList' => $pengerjaanList,
            'transaksi' => $record, // cukup kirim record aja, nanti di blade ambil relasi
        ]);
    }

    public function mount($record): void
    {
        parent::mount($record);

        if (session('success')) {
            Notification::make()
                ->title(session('success'))
                ->success()
                ->send();
        }
    }
}
