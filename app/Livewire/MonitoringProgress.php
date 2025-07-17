<?php

namespace App\Livewire;

use Filament\Widgets\Widget;

class MonitoringProgress extends Widget
{
    protected static string $view = 'livewire.monitoring-progress';
    public static ?string $maxWidth = 'full';

    public $rows = [];

    public function mount()
    {
        $this->rows = \App\Models\TransaksiMasuk::orderByDesc('id')->take(10)->get();
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
