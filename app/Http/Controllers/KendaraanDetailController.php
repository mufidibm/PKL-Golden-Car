<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanDetailController extends Controller
{
     public function show($id)
    {
        $kendaraan = Kendaraan::with('customer')->findOrFail($id);
        return view('kendaraan.detail', compact('kendaraan'));
    }
}
