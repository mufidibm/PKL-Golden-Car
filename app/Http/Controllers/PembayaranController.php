<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\TransaksiMasuk;
        use App\Filament\Resources\PembayaranResource;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function dariTransaksi($id)
    {
        // session()->forget(['bayar_transaksi_id', 'bayar_detail_items', 'bayar_total']);

        $transaksi = TransaksiMasuk::with([
            'pengerjaanServis.spareparts.barang',
            'pengerjaanServis.jasas.jasa',
            'customer'
        ])->findOrFail($id);

        $detailItems = [];
        $totalBayar = 0;

        foreach ($transaksi->pengerjaanServis as $pengerjaan) {
            // Ambil Sparepart
            foreach ($pengerjaan->spareparts as $item) {
                $subtotal = $item->qty * $item->harga;
                $totalBayar += $subtotal;
                $detailItems[] = [
                    'jenis_item' => 'sparepart',
                    'item_id' => $item->barang_id,
                    'nama_item' => $item->barang->nama_barang ?? '-',
                    'qty' => $item->qty,
                    'harga_satuan' => $item->harga,
                    'subtotal' => $subtotal,
                ];
            }

            // Ambil Jasa
            foreach ($pengerjaan->jasas as $jasa) {
                $subtotal = $jasa->harga;
                $totalBayar += $subtotal;
                $detailItems[] = [
                    'jenis_item' => 'jasa',
                    'item_id' => $jasa->jasa_id,
                    'nama_item' => $jasa->jasa->nama_jasa ?? '-',
                    'qty' => 1,
                    'harga_satuan' => $jasa->harga,
                    'subtotal' => $subtotal,
                ];
            }
        }


        session([
            'bayar_transaksi_id' => $transaksi->id,
            'bayar_detail_items' => $detailItems,
            'bayar_total' => $totalBayar,
        ]);


return redirect(PembayaranResource::getUrl('create'));

    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $transaksiId = $pembayaran->id_transaksi_masuk;

        // Hapus detail
        $pembayaran->detail()->delete();

        // Hapus pembayaran utama
        $pembayaran->delete();

        // Hapus session agar tidak muncul lagi saat create ulang
        // session()->forget([
        //     'bayar_transaksi_id',
        //     'bayar_detail_items',
        //     'bayar_total',
        // ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}
