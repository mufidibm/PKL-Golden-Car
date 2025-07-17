<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'stok',
        'harga_beli',
        'harga_jual',
        'keterangan',
    ];

    public function getLabelAttribute()
    {
        return $this->kode . ' - ' . $this->nama;
    }

    public function paketServis()
    {
        return $this->belongsToMany(PaketServis::class, 'paket_servis_item')
            ->withPivot('qty', 'harga')
            ->withTimestamps();
    }
}
