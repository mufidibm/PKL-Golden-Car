<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembayaran_id',
        'jenis_item',
        'item_id',
        'nama_item',
        'qty',
        'harga_satuan',
        'subtotal',
    ];


    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'item_id');
    }
}
