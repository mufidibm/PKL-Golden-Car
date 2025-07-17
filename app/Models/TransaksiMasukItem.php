<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasukItem extends Model
{
    use HasFactory;

    protected $table = 'transaksi_masuk_items';

    protected $fillable = [
        'transaksi_masuk_id',
        'barang_id',
        'qty',
        'harga',
        'subtotal',
    ];
}
