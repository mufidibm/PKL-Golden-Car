<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Setting extends Model implements HasMedia
{
    //use HasFactory;
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'nama_bengkel',
        'alamat',
        'telepon',
        'email',
    ];
}
