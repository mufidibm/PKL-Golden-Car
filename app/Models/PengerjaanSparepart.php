<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengerjaanSparepart extends Model
{
    use HasFactory;

    protected $fillable = ['pengerjaan_servis_id', 'barang_id', 'qty', 'harga', 'subtotal'];

    public function pengerjaan()
    {
        return $this->belongsTo(PengerjaanServis::class, 'pengerjaan_servis_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    protected static function booted(): void
    {
        static::created(function ($item) {
            $item->barang->decrement('stok', $item->qty);
        });

        static::deleting(function ($item) {
            $item->barang->increment('stok', $item->qty);
        });
    }
}
