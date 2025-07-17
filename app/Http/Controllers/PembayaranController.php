<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function dariTransaksi($id)
    {
        $transaksi = TransaksiMasuk::with(['pengerjaanServis.spareparts', 'customer'])->findOrFail($id);

        $detailItems = [];
        $totalBayar = 0;

        foreach ($transaksi->pengerjaanServis as $pengerjaan) {
            foreach ($pengerjaan->spareparts as $item) {
                $subtotal = $item->qty * $item->harga;
                $totalBayar += $subtotal;
                $detailItems[] = [
                    'jenis_item' => $item->jenis == 'jasa' ? 'servis' : 'sparepart',
                    'item_id' => $item->barang_id,
                    'nama_item' => $item->barang->nama_barang ?? '-',
                    'qty' => $item->qty,
                    'harga_satuan' => $item->harga,
                    'subtotal' => $subtotal,
                ];
            }
        }


        session([
            'bayar_transaksi_id' => $transaksi->id,
            'bayar_detail_items' => $detailItems,
            'bayar_total' => $totalBayar,
        ]);

        return redirect()->route('filament.admin.resources.pembayarans.create');
    }

    public function destroy($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        $transaksiId = $pembayaran->id_transaksi_masuk;

        // Hapus detail
        $pembayaran->detail()->delete();

        // Rollback stok sparepart jika perlu (opsional)

        // Hapus pembayaran utama
        $pembayaran->delete();

       return redirect()->back()->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}
