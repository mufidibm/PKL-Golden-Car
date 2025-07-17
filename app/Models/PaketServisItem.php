<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketServisItem extends Model
{
    use HasFactory;

      protected $fillable = ['paket_servis_id', 'barang_id', 'jumlah'];

    public function paketServis()
    {
        return $this->belongsTo(PaketServis::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
