<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $Pembayaran = Pembayaran::with([
            'transaksiMasuk.customer',
            'transaksiMasuk.kendaraan',
            'metodePembayaran',
            'detail',
        ])->findOrFail($id);

        return view('invoices.show', compact('Pembayaran'));
    }

    public function cetak($id)
    {
        $Pembayaran = Pembayaran::with([
            'transaksiMasuk.customer',
            'transaksiMasuk.kendaraan',
            'detail'
        ])->findOrFail($id);

        return view('invoices.cetak', compact('Pembayaran'));
    }
}
