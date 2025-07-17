<?php

namespace App\Filament\Resources\PengerjaanServisResource\Pages;

use App\Filament\Resources\PengerjaanServisResource;
use App\Models\PaketServis;
use App\Models\PengerjaanSparepart;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePengerjaanServis extends CreateRecord
{
    protected static string $resource = PengerjaanServisResource::class;

    
}
