<?php

namespace App\Http\Controllers;

use App\Models\PengerjaanJasa; // ✅ Tambahkan ini
use Illuminate\Http\Request;

class PengerjaanJasaController extends Controller
{
    public function destroy($id)
    {
        $jasa = PengerjaanJasa::findOrFail($id);
        $jasa->delete();

        return back()->with('success', 'Jasa berhasil dihapus.');
    }
}
