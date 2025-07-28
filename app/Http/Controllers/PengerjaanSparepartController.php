<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PengerjaanSparepart;
use Illuminate\Http\Request;

class PengerjaanSparepartController extends Controller
{
    public function destroy($id)
    {
        $sparepart = PengerjaanSparepart::findOrFail($id);

        // // Cek apakah item ini sparepart dan rollback stok
        // if ($sparepart->barang && $sparepart->qty) {
        //     Barang::where('id', $sparepart->barang_id)
        //         ->increment('stok', $sparepart->qty);
        // }

        $sparepart->delete();

        return back()->with('success', 'Sparepart berhasil dihapus dan stok dikembalikan.');
    }
}
