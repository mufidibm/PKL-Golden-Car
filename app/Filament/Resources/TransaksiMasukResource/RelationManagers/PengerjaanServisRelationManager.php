<?php

namespace App\Filament\Resources\TransaksiMasukResource\RelationManagers;

use App\Models\Barang;
use App\Models\PengerjaanSparepart;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;

class PengerjaanServisRelationManager extends RelationManager
{
    protected static string $relationship = 'pengerjaanServis';

    protected static ?string $title = 'Pengerjaan Servis';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('mekanik_id')
                ->relationship('mekanik', 'nama')
                ->required(),

            Select::make('status')
                ->options([
                    'Waiting' => 'Waiting',
                    'Sedang Dikerjakan' => 'Sedang Dikerjakan',
                    'Menunggu Sparepart' => 'Menunggu Sparepart',
                    'Pemeriksaan Akhir' => 'Pemeriksaan Akhir',
                    'Selesai' => 'Selesai',
                ])
                ->required(),

            DateTimePicker::make('mulai')->required(),
            DateTimePicker::make('selesai')->nullable(),
            Textarea::make('catatan')->label('Catatan')->nullable(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('mekanik.nama')->label('Mekanik'),
                TextColumn::make('status')->badge(),
                TextColumn::make('mulai')->dateTime(),
                TextColumn::make('selesai')->dateTime(),
                TextColumn::make('catatan')->limit(20),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('tambah_sparepart')
                ->label('Tambah Sparepart')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->form([
                    Repeater::make('items')
                        ->schema([
                            Select::make('barang_id')
                                ->label('Sparepart')
                                ->options(fn() => Barang::all()->pluck('nama_barang', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $barang = Barang::find($state);
                                    $harga = $barang?->harga_jual ?? 0;
                                    $set('harga', $harga);
                                    $set('subtotal', $get('qty') * $harga);
                                })
                                ->searchable()
                                ->required(),

                            TextInput::make('qty')
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(fn($state, callable $set, callable $get) =>
                                    $set('subtotal', $get('harga') * $state)
                                )
                                ->required(),

                            TextInput::make('harga')->numeric()->disabled()->dehydrated(true),
                            TextInput::make('subtotal')->numeric()->disabled()->dehydrated(true),
                        ])
                        ->columns(4)
                        ->createItemButtonLabel('Tambah'),
                ])
                ->action(function (array $data, $record) {
                    foreach ($data['items'] as $item) {
                        $harga = $item['harga'] ?? 0;
                        $qty = $item['qty'] ?? 0;
                        $subtotal = $item['subtotal'] ?? ($harga * $qty);

                        PengerjaanSparepart::create([
                            'pengerjaan_servis_id' => $record->id,
                            'barang_id' => $item['barang_id'],
                            'qty' => $qty,
                            'harga' => $harga,
                            'subtotal' => $subtotal,
                        ]);

                        Barang::find($item['barang_id'])?->decrement('stok', $qty);
                    }
                }),
            ]);
    }
}
