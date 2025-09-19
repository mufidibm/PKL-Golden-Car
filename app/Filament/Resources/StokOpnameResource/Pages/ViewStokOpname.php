<?php

namespace App\Filament\Resources\StokOpnameResource\Pages;

use App\Filament\Resources\StokOpnameResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStokOpname extends ViewRecord
{
    protected static string $resource = StokOpnameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    public function getTitle(): string
    {
        $record = $this->record;
        
        // Ambil nama item berdasarkan jenis inventory
        if ($record->jenis_inventory === 'barang' && $record->barang) {
            $itemName = $record->barang->nama_barang;
        } elseif ($record->jenis_inventory === 'stok' && $record->stok) {
            $itemName = $record->stok->nama_stok;
        } else {
            $itemName = 'Unknown Item';
        }
        
        return 'Detail Stok Opname - ' . $itemName;
    }
}