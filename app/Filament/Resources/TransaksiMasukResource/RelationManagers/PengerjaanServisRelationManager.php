<?php

namespace App\Filament\Resources\TransaksiMasukResource\RelationManagers;

use App\Models\Barang;
use App\Models\Jasa;
use App\Models\PengerjaanSparepart;
use App\Models\PengerjaanJasa;
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
use Filament\Notifications\Notification;

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
                    'Menunggu' => 'Menunggu',
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
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $barang = Barang::find($state);
                                        $harga = $barang?->harga_jual ?? 0;
                                        $stok = $barang?->stok ?? 0;
                                        $set('harga', $harga);
                                        $set('qty', 1);
                                        $set('subtotal', $harga * 1);
                                    })
                                    ->searchable()
                                    ->required(),

                                TextInput::make('qty')
                                    ->label(
                                        fn(callable $get) =>
                                        $get('barang_id')
                                            ? 'Qty (Stok: ' . (Barang::find($get('barang_id'))?->stok ?? 0) . ')'
                                            : 'Qty'
                                    )
                                    ->numeric()
                                    ->minValue(1)
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $barang = Barang::find($get('barang_id'));
                                        $stok = $barang?->stok ?? 0;
                                        $harga = $get('harga') ?? 0;

                                        if ($state > $stok) {
                                            $set('qty', $stok);
                                            Notification::make()
                                                ->title('Stok tidak mencukupi')
                                                ->body("Qty diubah menjadi $stok karena stok tidak mencukupi.")
                                                ->warning()
                                                ->send();
                                        }

                                        $qty = (int) $get('qty') ?: 0;
                                        $set('subtotal', $harga * $qty);
                                    }),

                                TextInput::make('harga')->numeric()->disabled()->dehydrated(true),
                                TextInput::make('subtotal')->numeric()->disabled()->dehydrated(true),
                            ])
                            ->columns(4)
                            ->createItemButtonLabel('Tambah'),
                    ])
                    ->action(function (array $data, $record) {
                        foreach ($data['items'] as $item) {
                            $barang = Barang::find($item['barang_id']);

                            if (! $barang || $barang->stok < $item['qty']) {
                                Notification::make()
                                    ->title('Gagal Menambahkan')
                                    ->body("Stok {$barang->nama_barang} tidak mencukupi.")
                                    ->danger()
                                    ->send();
                                continue;
                            }

                            PengerjaanSparepart::create([
                                'pengerjaan_servis_id' => $record->id,
                                'barang_id' => $item['barang_id'],
                                'qty' => $item['qty'],
                                'harga' => $item['harga'],
                                'subtotal' => $item['subtotal'],
                            ]);

                            // $barang->decrement('stok', $item['qty']);
                        }
                    }),

                Action::make('tambah_jasa')
                    ->label('Tambah Jasa')
                    ->icon('heroicon-o-plus-circle')
                    ->color('warning')
                    ->form(fn($record) => [
                        Repeater::make('items')
                            ->schema([
                                Select::make('jasa_id')
                                    ->label('Jasa')
                                    ->options(function () use ($record) {
                                        $asuransiId = $record->transaksiMasuk->asuransi_id ?? null;

                                        if (!$asuransiId) return [];

                                        return \App\Models\Jasa::where('asuransi_id', $asuransiId)
                                            ->pluck('nama_jasa', 'id');
                                    })
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $jasa = \App\Models\Jasa::find($state);
                                        $harga = $jasa?->harga ?? 0;
                                        $set('harga', $harga);
                                        $set('subtotal', $harga);
                                    }),

                                TextInput::make('harga')->numeric()->disabled()->dehydrated(true),
                                TextInput::make('subtotal')->numeric()->disabled()->dehydrated(true),
                            ])
                            ->columns(3)
                            ->createItemButtonLabel('Tambah'),
                    ])
                    ->action(function (array $data, $record) {
                        foreach ($data['items'] as $item) {
                            \App\Models\PengerjaanJasa::create([
                                'pengerjaan_servis_id' => $record->id,
                                'jasa_id' => $item['jasa_id'],
                                'harga' => $item['harga'],
                                'subtotal' => $item['subtotal'],
                            ]);
                        }
                    }),
            ]);
    }
}
