<?php

namespace App\Http\Controllers;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;

class TransaksiMasukController extends Controller
{
    public function estimasi(TransaksiMasuk $transaksi)
    {
        $Pembayaran = $transaksi->pembayaran; // Jika ada relasi pembayaran
        return view('transaksi.estimasi', compact('transaksi', 'Pembayaran'));
    }
}
