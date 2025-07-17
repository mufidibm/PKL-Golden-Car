<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];

    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class);
    }
}
