<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stoks';

    protected $fillable = [
        'kode_stok',
        'nama_stok',
        'stok',
        'kategori',
        'harga_beli',
        'satuan',
        'keterangan',
    ];

    protected $casts = [
        'stok' => 'integer',
        'harga_beli' => 'decimal:2',
    ];

    // Enum untuk kategori
    public const KATEGORI_OPTIONS = [
        'Bahan Paint',
        'Bahan non Paint',
        'Tools',
    ];

    // Accessor untuk format harga
    public function getFormattedHargaBeliAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope untuk stok rendah
    public function scopeStokRendah($query, $batas = 10)
    {
        return $query->where('stok', '<=', $batas);
    }

    // Scope untuk stok habis
    public function scopeStokHabis($query)
    {
        return $query->where('stok', 0);
    }
}