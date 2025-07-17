<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class LaporanPendapatanExport implements FromView
{
    protected $data;
    protected $tanggalMulai;
    protected $tanggalSelesai;
    protected $jumlahTransaksi;
    protected $totalPendapatan;

    public function __construct($data, $tanggalMulai, $tanggalSelesai, $jumlahTransaksi, $totalPendapatan)
    {
        $this->data = $data;
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->jumlahTransaksi = $jumlahTransaksi;
        $this->totalPendapatan = $totalPendapatan;
    }

    public function view(): View
    {
        return view('exports.laporan-pendapatan-excel', [
            'data' => $this->data,
            'tanggalMulai' => $this->tanggalMulai,
            'tanggalSelesai' => $this->tanggalSelesai,
            'jumlahTransaksi' => $this->jumlahTransaksi,
            'totalPendapatan' => $this->totalPendapatan,
        ]);
    }
}
