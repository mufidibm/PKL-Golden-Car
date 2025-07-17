<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'no_polisi',
        'tipe',
        'merek',
        'tahun',
        'warna',
        'jenis_kendaraan'
    ];

    public function customer()
{
    return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
}

    
}
