<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LaporanTransaksi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan-transaksi';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
