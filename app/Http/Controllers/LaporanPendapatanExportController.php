<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use PDF;

class LaporanPendapatanExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredData($request);
        $totalPendapatan = 0;
        foreach ($data as $pembayaran) {
            foreach ($pembayaran->detail as $detail) {
                $hargaBeli = $detail->barang->harga_beli ?? 0;
                $totalPendapatan += (($detail->harga_satuan - $hargaBeli) * $detail->qty);
            }
        }
        $jumlahTransaksi = $data->count();
        $tanggalMulai = $request->input('tanggalMulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggalSelesai', now()->format('Y-m-d'));
        $pdf = PDF::loadView('exports.laporan-pendapatan-pdf', [
            'data' => $data,
            'totalPendapatan' => $totalPendapatan,
            'jumlahTransaksi' => $jumlahTransaksi,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
        return $pdf->download('laporan_pendapatan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $tanggalMulai = $request->input('tanggalMulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggalSelesai', now()->format('Y-m-d'));

        $query = Pembayaran::query()
            ->whereBetween('created_at', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59'])
            ->with(['transaksiMasuk.kendaraan.customer', 'metodePembayaran', 'detail.barang']);

        if ($request->filled('customerId')) {
            $query->whereHas('transaksiMasuk.kendaraan.customer', function ($q) use ($request) {
                $q->where('id', $request->customerId);
            });
        }

        if ($request->filled('metodePembayaranId')) {
            $query->where('metode_pembayaran_id', $request->metodePembayaranId);
        }

        $data = $query->orderByDesc('created_at')->get();

        $jumlahTransaksi = $data->count();
        $totalPendapatan = 0;
        foreach ($data as $pembayaran) {
            foreach ($pembayaran->detail as $detail) {
                $hargaBeli = $detail->barang->harga_beli ?? 0;
                $totalPendapatan += (($detail->harga_satuan - $hargaBeli) * $detail->qty);
            }
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanPendapatanExport($data, $tanggalMulai, $tanggalSelesai, $jumlahTransaksi, $totalPendapatan),
            'laporan_pendapatan.xlsx'
        );
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
        return $query->with(['transaksiMasuk.kendaraan.customer', 'metodePembayaran', 'detail.barang'])->orderByDesc('created_at')->get();
    }
}
