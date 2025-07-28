<?php

namespace App\Filament\Resources\AsuransiResource\Pages;

use App\Filament\Resources\AsuransiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsuransi extends EditRecord
{
    protected static string $resource = AsuransiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
