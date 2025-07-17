<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'stok_lama',
        'stok_baru',
        'selisih',
        'keterangan',
        'tanggal_opname',
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class);
    }

    protected static function booted()
    {
        static::created(function ($opname) {
            $opname->barang->update([
                'stok' => $opname->stok_baru
            ]);
        });
    }
}
