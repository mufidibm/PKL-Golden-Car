<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LaporanPendapatan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan-pendapatan';

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

    public function getDataPendapatan()
    {
        $query = \App\Models\Pembayaran::query()
            ->whereBetween('created_at', [
                $this->tanggalMulai . ' 00:00:00',
                $this->tanggalSelesai . ' 23:59:59',
            ]);
        if ($this->customerId) {
            $query->whereHas('transaksiMasuk.kendaraan.customer', function($q) {
                $q->where('id', $this->customerId);
            });
        }
        if ($this->metodePembayaranId) {
            $query->where('metode_pembayaran_id', $this->metodePembayaranId);
        }
        return $query->with(['transaksiMasuk.kendaraan.customer', 'metodePembayaran', 'detail.barang'])->orderByDesc('created_at')->get();
    }

    public function getRingkasan()
    {
        $data = $this->getDataPendapatan();
        $totalPendapatan = 0;
        foreach ($data as $pembayaran) {
            foreach ($pembayaran->detail as $detail) {
                $hargaBeli = $detail->barang->harga_beli ?? 0;
                $totalPendapatan += (($detail->harga_satuan - $hargaBeli) * $detail->qty);
            }
        }
        return [
            'totalPendapatan' => $totalPendapatan,
            'jumlahTransaksi' => $data->count(),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('laporan pendapatan');
    }
}
