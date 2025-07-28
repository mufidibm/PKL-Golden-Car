<?php

namespace App\Filament\Resources\TransaksiMasukResource\RelationManagers;

use App\Models\PengerjaanJasa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PengerjaanJasaRelationManager extends RelationManager
{
    protected static string $relationship = 'pengerjaanJasa';

    protected static ?string $title = 'Jasa yang Digunakan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jasa_id')
                    ->label('Jasa')
                    ->relationship('jasa', 'nama_jasa')
                    ->required(),
                    
                Forms\Components\TextInput::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                    
                Forms\Components\TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->required()
                    ->prefix('Rp'),
                    
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->required()
                    ->prefix('Rp'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jasa.nama_jasa')
            ->columns([
                Tables\Columns\TextColumn::make('jasa.nama_jasa')
                    ->label('Nama Jasa')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('qty')
                    ->label('Qty')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada jasa yang digunakan')
            ->emptyStateDescription('Tambahkan jasa yang digunakan dalam pengerjaan servis ini.')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver');
    }
}