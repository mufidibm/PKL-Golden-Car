<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengerjaanServisResource\Pages;
use App\Filament\Resources\PengerjaanServisResource\RelationManagers\PengerjaanSparepartRelationManager;
use App\Models\PengerjaanServis;
use App\Models\PengerjaanSparepart;
use App\Models\Barang;
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
            TextInput::make('catatan')->label('Catatan')->nullable(),
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
                        default => 'gray',
                    }),

                TextColumn::make('mulai')->dateTime(),
                TextColumn::make('selesai')->dateTime(),
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
                                    ->options(fn() => Barang::all()->mapWithKeys(fn($item) => [
                                        $item->id => $item->kode_barang . ' - ' . $item->nama_barang
                                    ]))
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
                                    ->label('Qty')
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(
                                        fn($state, callable $set, callable $get) =>
                                        $set('subtotal', $state * $get('harga'))
                                    )
                                    ->required(),

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
                            ->createItemButtonLabel('Tambah Sparepart')
                            ->columns(4),
                    ])
                    ->action(function (array $data, PengerjaanServis $record) {
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

                            // Barang::find($item['barang_id'])?->decrement('stok', $qty);
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengerjaanServis::route('/'),
            'create' => Pages\CreatePengerjaanServis::route('/create'),
            'edit' => Pages\EditPengerjaanServis::route('/{record}/edit'),
        ];
    }
}
