<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    use HasFactory;

    protected $table = 'jasa';

    protected $fillable = [
        'nama_jasa',
        'harga',
        'asuransi_id'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    public function asuransi()
    {
        return $this->belongsTo(Asuransi::class);
    }
    // Jika tidak menggunakan timestamps Laravel, uncomment baris di bawah
    // public $timestamps = false;
}
