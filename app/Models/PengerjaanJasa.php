<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengerjaanJasa extends Model
{
    use HasFactory;

    protected $table = 'pengerjaan_jasa';

    protected $fillable = [
        'pengerjaan_servis_id',
        'jasa_id',
        'asuransi_id',
        'qty',
        'harga',
        'subtotal'
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function jasa()
    {
        return $this->belongsTo(\App\Models\Jasa::class);
    }

    public function asuransi()
    {
        return $this->belongsTo(Asuransi::class);
    }
}
