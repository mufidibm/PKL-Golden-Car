<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaksi_masuk',
        'metode_pembayaran_id',
        'total_bayar',
        'kode_invoice',
        'dibayar',
        'kembalian',
        'kasir_id',
    ];

    public function detail()
    {
        return $this->hasMany(\App\Models\PembayaranDetail::class);
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function transaksiMasuk()
{
    return $this->belongsTo(\App\Models\TransaksiMasuk::class, 'id_transaksi_masuk');
}

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }
}
