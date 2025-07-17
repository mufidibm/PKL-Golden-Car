<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPembayaranExport implements FromView
{
    public $data;
    public $totalPembayaran;
    public $jumlahTransaksi;
    public $tanggalMulai;
    public $tanggalSelesai;

    public function __construct($data, $totalPembayaran, $jumlahTransaksi, $tanggalMulai, $tanggalSelesai)
    {
        $this->data = $data;
        $this->totalPembayaran = $totalPembayaran;
        $this->jumlahTransaksi = $jumlahTransaksi;
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
    }

    public function view(): View
    {
        return view('exports.laporan-pembayaran-excel', [
            'data' => $this->data,
            'totalPembayaran' => $this->totalPembayaran,
            'jumlahTransaksi' => $this->jumlahTransaksi,
            'tanggalMulai' => $this->tanggalMulai,
            'tanggalSelesai' => $this->tanggalSelesai,
        ]);
    }
}
