<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KendaraanDetailController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengerjaanSparepartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanPembayaranExportController;
use App\Http\Controllers\LaporanPendapatanExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin/kendaraan-detail/{id}', [KendaraanDetailController::class, 'show'])->name('kendaraan.detail');
Route::delete('/sparepart/{id}', [PengerjaanSparepartController::class, 'destroy'])->name('sparepart.delete');
Route::post('/pembayaran/dari-transaksi/{transaksi}', [PembayaranController::class, 'dariTransaksi'])
    ->name('pembayaran.dari-transaksi');
Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

Route::get('/invoice/{id}', [InvoiceController::class, 'show']);
Route::get('/invoice/{id}/cetak', [InvoiceController::class, 'cetak'])->name('invoice.cetak');

Route::get('laporan-pembayaran/export-pdf', [LaporanPembayaranExportController::class, 'exportPdf'])->name('laporan-pembayaran.export-pdf');
Route::get('laporan-pendapatan/export-pdf', [LaporanPendapatanExportController::class, 'exportPdf'])->name('laporan-pendapatan.export-pdf');


Route::get('laporan-pembayaran/export-excel', [LaporanPembayaranExportController::class, 'exportExcel'])->name('laporan-pembayaran.export-excel');
Route::get('laporan-pendapatan/export-excel', [LaporanPendapatanExportController::class, 'exportExcel'])->name('laporan-pendapatan.export-excel');


