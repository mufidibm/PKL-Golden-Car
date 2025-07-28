<?php

namespace App\Filament\Resources\JasaResource\Pages;

use App\Filament\Resources\JasaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Asuransi;
use Filament\Tables\Actions\CreateAction;
use Filament\Widgets\TableWidget;
use Filament\Tables\Table as WidgetTable;
use App\Filament\Widgets\AsuransiTableWidget;

class ListJasas extends ListRecords
{
    protected static string $resource = JasaResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            AsuransiTableWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jasa')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Master Jasa';
    }
}
