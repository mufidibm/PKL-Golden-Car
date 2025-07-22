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

        $sparepart->delete();

        return back()->with('success', 'Sparepart berhasil dihapus dan stok dikembalikan.');
    }
}
