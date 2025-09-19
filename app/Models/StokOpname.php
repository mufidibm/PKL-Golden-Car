<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $table = 'stok_opnames';

    protected $fillable = [
        'jenis_inventory',
        'item_id',
        'stok_lama',
        'stok_baru',
        'selisih',
        'keterangan',
        'tanggal_opname',
    ];

    protected $casts = [
        'tanggal_opname' => 'date',
        'stok_lama' => 'integer',
        'stok_baru' => 'integer',
        'selisih' => 'integer',
    ];

    // Relationship untuk Barang (Sparepart) - TANPA WHERE CLAUSE
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'item_id');
    }

    // Relationship untuk Stok - TANPA WHERE CLAUSE  
    public function stok()
    {
        return $this->belongsTo(Stok::class, 'item_id');
    }

    // Accessor untuk mendapatkan item yang benar berdasarkan jenis
    public function getItemAttribute()
    {
        if ($this->jenis_inventory === 'barang') {
            return $this->barang;
        } elseif ($this->jenis_inventory === 'stok') {
            return $this->stok;
        }
        return null;
    }

    // Accessor untuk nama item
    public function getItemNameAttribute()
    {
        if ($this->jenis_inventory === 'barang' && $this->barang) {
            return $this->barang->nama_barang;
        } elseif ($this->jenis_inventory === 'stok' && $this->stok) {
            return $this->stok->nama_stok;
        }
        return '-';
    }

    // Accessor untuk kode item
    public function getItemCodeAttribute()
    {
        if ($this->jenis_inventory === 'barang' && $this->barang) {
            return $this->barang->kode_barang;
        } elseif ($this->jenis_inventory === 'stok' && $this->stok) {
            return $this->stok->kode_stok;
        }
        return '-';
    }

    // Auto update stok saat opname dibuat/diupdate
    protected static function booted()
    {
        static::created(function ($opname) {
            $opname->updateInventoryStok();
        });

        static::updated(function ($opname) {
            $opname->updateInventoryStok();
        });
    }

    // Method untuk update stok inventory
    public function updateInventoryStok()
    {
        if ($this->jenis_inventory === 'barang' && $this->barang) {
            $this->barang->update(['stok' => $this->stok_baru]);
        } elseif ($this->jenis_inventory === 'stok' && $this->stok) {
            $this->stok->update(['stok' => $this->stok_baru]);
        }
    }

    // Scope untuk filter berdasarkan jenis inventory
    public function scopeJenisInventory($query, $jenis)
    {
        return $query->where('jenis_inventory', $jenis);
    }

    // Scope untuk selisih positif
    public function scopeSelisihPositif($query)
    {
        return $query->where('selisih', '>', 0);
    }

    // Scope untuk selisih negatif
    public function scopeSelisihNegatif($query)
    {
        return $query->where('selisih', '<', 0);
    }
}