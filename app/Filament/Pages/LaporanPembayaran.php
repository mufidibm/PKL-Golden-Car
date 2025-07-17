<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;

class LaporanPembayaran extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan-pembayaran';

    public static ?string $navigationGroup = 'Laporan';

    public $tanggalMulai;
    public $tanggalSelesai;
    public $customerId;
    public $customers = [];
    public $metodePembayaranId;
    public $metodePembayaranList = [];

    public function mount()
    {
        $this->tanggalMulai = request('tanggalMulai', now()->startOfMonth()->format('Y-m-d'));
        $this->tanggalSelesai = request('tanggalSelesai', now()->format('Y-m-d'));
        $this->customerId = request('customerId', '');
        $this->metodePembayaranId = request('metodePembayaranId', '');
        $this->customers = \App\Models\Customer::orderBy('nama')->pluck('nama', 'id')->toArray();
        $this->metodePembayaranList = \App\Models\MetodePembayaran::orderBy('nama_metode')->pluck('nama_metode', 'id')->toArray();
    }

    public function updated($property)
    {
        $ringkasan = $this->getRingkasanDanChart();
        $this->dispatchBrowserEvent('refreshChart', [
            array_keys($ringkasan['perHari']->toArray()),
            array_values($ringkasan['perHari']->toArray())
        ]);
    }

    public function getDataPembayaran()
    {
        $query = \App\Models\Pembayaran::query()
            ->whereBetween('created_at', [
                $this->tanggalMulai . ' 00:00:00',
                $this->tanggalSelesai . ' 23:59:59',
            ]);
        if ($this->customerId) {
            $query->whereHas('transaksiMasuk.kendaraan.customer', function ($q) {
                $q->where('id', $this->customerId);
            });
        }
        if ($this->metodePembayaranId) {
            $query->where('metode_pembayaran_id', $this->metodePembayaranId);
        }
        return $query->with(['transaksiMasuk.kendaraan.customer', 'metodePembayaran'])->orderByDesc('created_at')->get();
    }

    public function getRingkasanDanChart()
    {
        $data = $this->getDataPembayaran();
        $totalPembayaran = $data->sum('total_bayar');
        $jumlahTransaksi = $data->count();
        $perHari = $data->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('total_bayar');
        });
        return [
            'totalPembayaran' => $totalPembayaran,
            'jumlahTransaksi' => $jumlahTransaksi,
            'perHari' => $perHari,
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('laporan pembayaran');
    }
}
