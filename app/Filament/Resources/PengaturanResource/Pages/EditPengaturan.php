<?php

namespace App\Filament\Resources\PengaturanResource\Pages;

use App\Filament\Resources\PengaturanResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class EditPengaturan extends EditRecord
{
    protected static string $resource = PengaturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormSchema(): array
{
    return [
        TextInput::make('nama_bengkel')->label('Nama Bengkel')->required(),
        Textarea::make('alamat')->label('Alamat')->required(),
        TextInput::make('telepon')->label('No. Telepon')->required(),
        TextInput::make('email')->label('Email')->email(),
        FileUpload::make('logo')
            ->label('Logo')
            ->image()
            ->directory('logo')
            ->imageEditor()
            ->preserveFilenames(),
    ];
}
}
