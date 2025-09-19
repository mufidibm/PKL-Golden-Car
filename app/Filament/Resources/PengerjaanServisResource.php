<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengerjaanServisResource\Pages;
use App\Filament\Resources\PengerjaanServisResource\RelationManagers\PengerjaanSparepartRelationManager;
use App\Models\PengerjaanServis;
use App\Models\PengerjaanSparepart;
use App\Models\Barang;
use App\Models\PengerjaanJasa;
use App\Models\Jasa;
use Filament\Forms;
use Filament\Forms\Components\{DatePicker, DateTimePicker, Repeater, Select, Textarea, TextInput};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\{Action, EditAction};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\{Filter, SelectFilter};
use Filament\Tables\Table;
use App\Models\TransaksiMasuk;
use Filament\Notifications\Notification;

class PengerjaanServisResource extends Resource
{
    protected static ?string $model = PengerjaanServis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Service Manajemen';

    protected static ?string $label = 'Pengerjaan Service';
    protected static ?string $pluralLabel = 'Pengerjaan Service';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('transaksi_masuk_id')
                ->label('Transaksi Masuk')
                ->searchable()
                ->getSearchResultsUsing(function (string $search) {
                    return TransaksiMasuk::with('kendaraan')
                        ->whereHas('kendaraan', function ($query) use ($search) {
                            $query->where('no_polisi', 'like', "%{$search}%");
                        })
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [
                                $item->id => $item->kendaraan->no_polisi . ' - ' . $item->created_at->format('d/m/Y'),
                            ];
                        });
                })
                ->getOptionLabelUsing(function ($value): ?string {
                    $item = TransaksiMasuk::with('kendaraan')->find($value);
                    return $item ? $item->kendaraan->no_polisi . ' - ' . $item->created_at->format('d/m/Y') : null;
                })
                ->required(),

            Select::make('mekanik_id')
                ->label('Mekanik')
                ->relationship('mekanik', 'nama')
                ->searchable()
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

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->withSum('spareparts', 'subtotal'))
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),

                TextColumn::make('transaksi.kendaraan.no_polisi')
                    ->label('No Polisi')
                    ->sortable()
                    ->searchable()
                    ->url(fn($record) => route('kendaraan.detail', ['id' => $record->transaksi->kendaraan->id]))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->tooltip('Lihat detail kendaraan'),

                TextColumn::make('transaksi.kendaraan.customer.nama')
                    ->label('Nama Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('mekanik.nama')->label('Mekanik')->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'Menunggu' => 'gray',
                        'Sedang Dikerjakan' => 'warning',
                        'Menunggu Sparepart' => 'danger',
                        'Pemeriksaan Akhir' => 'info',
                        'Selesai' => 'success',
                    }),

                TextColumn::make('mulai')->dateTime(),
                TextColumn::make('selesai')->dateTime(),
                TextColumn::make('catatan')->limit(20),
                TextColumn::make('spareparts_sum_subtotal')
                    ->label('Total Biaya Sparepart')
                    ->money('IDR', true)
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ?? 0),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pengerjaan')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Sedang Dikerjakan' => 'Sedang Dikerjakan',
                        'Menunggu Sparepart' => 'Menunggu Sparepart',
                        'Pemeriksaan Akhir' => 'Pemeriksaan Akhir',
                        'Selesai' => 'Selesai',
                    ]),

                Filter::make('mulai')
                    ->form([
                        DatePicker::make('from')->label('Mulai Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('mulai', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('mulai', '<=', $data['until']));
                    }),
            ])
            ->actions([
                EditAction::make(),
                Action::make('tambah_sparepart')
                    ->label('Tambah Sparepart')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Repeater::make('items')
                            ->label('Sparepart Digunakan')
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

                                TextInput::make('harga')
                                    ->label('Harga')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true),
                            ])
                            ->createItemButtonLabel('Tambah')
                            ->columns(4),
                    ])
                    ->action(function (array $data, PengerjaanServis $record) {
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

                            $barang->decrement('stok', $item['qty']);
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PengerjaanSparepartRelationManager::class,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengerjaanServis::route('/'),
            'create' => Pages\CreatePengerjaanServis::route('/create'),
            'edit' => Pages\EditPengerjaanServis::route('/{record}/edit'),
        ];
    }
}
