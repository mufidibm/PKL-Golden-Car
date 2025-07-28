<?php

namespace App\Filament\Resources\PengaturanResource\Pages;

use App\Filament\Resources\PengaturanResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

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
        SpatieMediaLibraryFileUpload::make('logo')
            ->label('Logo')
            ->collection('logo')
            ->disk('public')
            ->directory('logo')
            ->image()
            ->imageEditor()
            ->preserveFilenames(),
    ];
}
}
