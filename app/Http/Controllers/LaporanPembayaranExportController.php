<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Customer;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Exports\LaporanPembayaranExport;

class LaporanPembayaranExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredData($request);
        $totalPembayaran = $data->sum('total_bayar');
        $jumlahTransaksi = $data->count();
        $tanggalMulai = $request->input('tanggalMulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggalSelesai', now()->format('Y-m-d'));
        $pdf = PDF::loadView('exports.laporan-pembayaran-pdf', [
            'data' => $data,
            'totalPembayaran' => $totalPembayaran,
            'jumlahTransaksi' => $jumlahTransaksi,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
        return $pdf->download('laporan_pembayaran.pdf');
    }


    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredData($request);
        $totalPembayaran = $data->sum('total_bayar');
        $jumlahTransaksi = $data->count();
        $tanggalMulai = $request->input('tanggalMulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggalSelesai', now()->format('Y-m-d'));

        $export = new LaporanPembayaranExport($data, $totalPembayaran, $jumlahTransaksi, $tanggalMulai, $tanggalSelesai);

        return Excel::download($export, 'laporan_pembayaran.xlsx');
    }


    private function getFilteredData(Request $request)
    {
        $query = Pembayaran::query()
            ->whereBetween('created_at', [
                $request->input('tanggalMulai', now()->startOfMonth()->format('Y-m-d')) . ' 00:00:00',
                $request->input('tanggalSelesai', now()->format('Y-m-d')) . ' 23:59:59',
            ]);
        if ($request->filled('customerId')) {
            $query->whereHas('transaksiMasuk.kendaraan.customer', function ($q) use ($request) {
                $q->where('id', $request->input('customerId'));
            });
        }
        if ($request->filled('metodePembayaranId')) {
            $query->where('metode_pembayaran_id', $request->input('metodePembayaranId'));
        }
        return $query->with(['transaksiMasuk.kendaraan.customer', 'metodePembayaran'])->orderByDesc('created_at')->get();
    }
}
