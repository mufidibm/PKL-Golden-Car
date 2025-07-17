<?php

namespace App\Livewire;

use Filament\Widgets\Widget;

class StatusKendaraan extends Widget
{
    protected static string $view = 'livewire.status-kendaraan';
    public static ?string $maxWidth = 'full';

    public $waiting = 0;
    public $sedangDikerjakan = 0;
    public $menungguSparepart = 0;
    public $pemeriksaanAkhir = 0;
    public $selesai = 0;
    public $total = 0;

    public function mount()
    {
        $this->waiting = \App\Models\PengerjaanServis::where('status', 'Waiting')->count();
        $this->sedangDikerjakan = \App\Models\PengerjaanServis::where('status', 'Sedang Dikerjakan')->count();
        $this->menungguSparepart = \App\Models\PengerjaanServis::where('status', 'Menunggu Sparepart')->count();
        $this->pemeriksaanAkhir = \App\Models\PengerjaanServis::where('status', 'Pemeriksaan Akhir')->count();
        $this->selesai = \App\Models\PengerjaanServis::where('status', 'Selesai')->count();
        $this->total = $this->waiting + $this->sedangDikerjakan + $this->menungguSparepart + $this->pemeriksaanAkhir + $this->selesai;
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
