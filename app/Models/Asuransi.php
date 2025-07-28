<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asuransi extends Model
{
    protected $fillable = ['nama'];

    public function jasas()
    {
        return $this->hasMany(Jasa::class);
    }
}