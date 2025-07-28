<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    use HasFactory;
    protected $table = 'transaksi_masuk';
    protected $fillable = [
        'kendaraan_id',
        'paket_servis_id',
        'status',
        'waktu_masuk',
        'estimasi_biaya',
        'keluhan',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(\App\Models\Kendaraan::class, 'kendaraan_id');
    }

    public function asuransi()
    {
        return $this->belongsTo(Asuransi::class);
    }

    public function paketServis()
    {
        return $this->belongsTo(PaketServis::class);
    }

    public function pengerjaanServis()
    {
        return $this->hasMany(PengerjaanServis::class);
        
    }

    public function getLabelAttribute()
    {
        return $this->kendaraan->no_polisi . ' - ' . $this->created_at->format('d/m/Y');
    }

    public function getTitleAttribute()
    {
        return ($this->kendaraan->no_polisi ?? 'Tanpa Nomor Polisi') . ' - ' . $this->created_at->format('d/m/Y');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'kendaraan_id', 'id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_transaksi_masuk');
    }
}
