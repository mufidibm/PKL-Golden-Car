<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMasuk;
use App\Models\Setting;
use App\Models\Jasa;
use App\Models\Barang;
use App\Models\PengerjaanJasa;
use App\Models\PengerjaanSparepart;
use Illuminate\Http\Request;

class EstimasiController extends Controller
{
    public function cetak(TransaksiMasuk $transaksi)
    {
        // Ambil data yang diperlukan untuk estimasi
        $setting = Setting::first();
        
        // Load relasi dasar yang pasti ada
        $transaksi->load([
            'asuransi',
            'kendaraan.customer',
            'pengerjaanServis.pengerjaanJasa.jasa',
            'pengerjaanServis.pengerjaanSparepart.barang',
        ]);
        
        // Ambil jasa menggunakan query langsung untuk menghindari error relasi
        $jasa = collect();
        if ($transaksi->pengerjaanServis->isNotEmpty()) {
            $pengerjaanServisIds = $transaksi->pengerjaanServis->pluck('id');
            
            // Query langsung ke tabel pengerjaan_jasa
            $pengerjaanJasaList = PengerjaanJasa::whereIn('pengerjaan_servis_id', $pengerjaanServisIds)
                ->with('jasa')
                ->get();
            
           foreach ($pengerjaanJasaList as $pengerjaanJasa) {
    if ($pengerjaanJasa->jasa) {
        $jasa->push((object)[
            'nama_jasa' => $pengerjaanJasa->jasa->nama_jasa,
            'harga' => $pengerjaanJasa->harga,
            'subtotal' => $pengerjaanJasa->subtotal,
        ]);
    }
}

        }
        
        // Ambil sparepart menggunakan query langsung
        $sparepart = collect();
        if ($transaksi->pengerjaanServis->isNotEmpty()) {
            $pengerjaanServisIds = $transaksi->pengerjaanServis->pluck('id');
            
            // Query langsung ke tabel pengerjaan_sparepart
            $pengerjaanSparepartList = PengerjaanSparepart::whereIn('pengerjaan_servis_id', $pengerjaanServisIds)
                ->with('barang')
                ->get();
            
           foreach ($pengerjaanSparepartList as $pengerjaanSparepart) {
    if ($pengerjaanSparepart->barang) {
        $sparepart->push((object)[
            'nama_barang' => $pengerjaanSparepart->barang->nama_barang,
            'harga_jual' => $pengerjaanSparepart->harga,
            'qty' => $pengerjaanSparepart->qty,
            'subtotal' => $pengerjaanSparepart->subtotal,
        ]);
    }
}

        }
        
        // Hitung total biaya
        $totalJasa = $jasa->sum('harga');
        $totalSparepart = $sparepart->sum('harga_jual');
        $totalBayar = $totalJasa + $totalSparepart;
        
        return view('transaksi.estimasi', compact(
            'transaksi',
            'setting',
            'jasa',
            'sparepart',
            'totalBayar'
        ));
    }
}