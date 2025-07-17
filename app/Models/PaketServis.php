<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketServis extends Model
{
    use HasFactory;

    protected $fillable = ['nama_paket', 'deskripsi', 'harga'];

    public function items()
    {
        return $this->hasMany(PaketServisItem::class);
    }

    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'paket_servis_item')
            ->withPivot('qty', 'harga')
            ->withTimestamps();
    }
}
