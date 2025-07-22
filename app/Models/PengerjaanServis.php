<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengerjaanServis extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_masuk_id',
        'mekanik_id',
        'status',
        'catatan',
        'mulai',
        'selesai',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiMasuk::class, 'transaksi_masuk_id');
    }

    public function mekanik()
    {
        return $this->belongsTo(Pegawai::class, 'mekanik_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function spareparts()
    {
        return $this->hasMany(\App\Models\PengerjaanSparepart::class);
    }

    public function paketServis()
    {
        return $this->belongsTo(PaketServis::class, 'paket_servis_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function transaksiMasuk()
    {
        return $this->belongsTo(\App\Models\TransaksiMasuk::class, 'transaksi_masuk_id');
    }

    protected static function booted()
    {
        static::saved(function ($pengerjaan) {
            // Ubah status Transaksi Masuk mengikuti status pengerjaan
            $pengerjaan->transaksi?->update([
                'status' => match ($pengerjaan->status) {
                    'Menunggu' => 'Menunggu',
                    'Waiting' => 'Menunggu',
                    'Sedang Dikerjakan' => 'Sedang Dikerjakan',
                    'Menunggu Sparepart' => 'Menunggu Sparepart',
                    'Pemeriksaan Akhir' => 'Pemeriksaan Akhir',
                    'Selesai' => 'Selesai',
                    default => 'Menunggu',
                },
            ]);
        });
    }
}
