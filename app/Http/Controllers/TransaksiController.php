<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function cetakEstimasi($id)
    {
        $Pembayaran = \App\Models\Pembayaran::with([
            'detail',
            'transaksiMasuk.kendaraan.customer',
            'transaksiMasuk.asuransi',
            'metodePembayaran'
        ])->findOrFail($id);

        return view('transaksi.estimasi', compact('Pembayaran'));
    }
}
