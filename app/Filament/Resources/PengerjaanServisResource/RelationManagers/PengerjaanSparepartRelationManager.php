<?php

namespace App\Filament\Resources\PengerjaanServisResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;


class PengerjaanSparepartRelationManager extends RelationManager

{
    protected static string $relationship = 'spareparts';
    protected static ?string $title = 'Sparepart Dipakai';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barang.nama_barang')->label('Sparepart'),
                TextColumn::make('qty')->label('Qty'),
                TextColumn::make('harga')->money('IDR', true),
                TextColumn::make('subtotal')
                    ->money('IDR', true)
                    ->label('Subtotal')
                    ->summarize(
                        Sum::make()->label('Total Biaya')
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }
}
