<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;

class EstimateController extends Controller
{
    public function cetak($id)
    {
        $transaksi = TransaksiMasuk::with(['kendaraan.customer', 'asuransi'])->findOrFail($id);
        $setting = \App\Models\Setting::first();
        $jasa = \App\Models\Jasa::where('asuransi_id', $transaksi->asuransi_id)->get();
        $sparepart = \App\Models\Barang::all(); // Semua barang ditampilkan

        // Hitung total
        $totalJasa = 0;
        $totalSparepart = 0;

        foreach ($jasa as $item) {
            $totalJasa += $item->harga;
        }

        foreach ($sparepart as $item) {
            $subtotal = $item->harga_jual * 1; // Jumlah default 1
            $totalSparepart += $subtotal;
        }

        $ppnJasa = 0; // PPN nonaktif
        $ppnSparepart = 0; // PPN nonaktif
        $totalBayar = $totalJasa + $totalSparepart;

        return view('transaksi.estimasi', compact('transaksi', 'jasa', 'sparepart', 'setting', 'ppnJasa', 'ppnSparepart', 'totalBayar'));
    }
}