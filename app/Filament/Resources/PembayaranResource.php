<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use App\Models\Asuransi;
use Filament\Forms;
use Filament\Forms\Components\{Grid, Hidden, Repeater, Select, TextInput};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 99;

    protected static ?string $navigationGroup = 'Lainnya';

    public static function form(Form $form): Form
    {
        if ($form->getOperation() === 'create' && (!session()->has('bayar_transaksi_id') || !session()->has('bayar_detail_items') || !session()->has('bayar_total'))) {
            abort(403, 'Data transaksi tidak ditemukan. Silakan mulai proses pembayaran dari halaman Transaksi Masuk.');
        }

        return $form->schema([
            Grid::make(2)->schema([
                Toggle::make('gunakan_ppn_jasa')
                    ->label('Gunakan PPN 11% pada Jasa')
                    ->reactive()
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        session(['gunakan_ppn_jasa' => $state]);
                        static::hitungTotal($set, $get);
                    }),

                Toggle::make('gunakan_ppn_sparepart')
                    ->label('Gunakan PPN 11% untuk Sparepart')
                    ->reactive()
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        session(['gunakan_ppn_sparepart' => $state]);
                        static::hitungTotal($set, $get);
                    }),

                Hidden::make('id_transaksi_masuk')
                    ->default(fn() => session('bayar_transaksi_id'))
                    ->required(),

                Select::make('metode_pembayaran_id')
                    ->label('Metode Pembayaran')
                    ->relationship('metodePembayaran', 'nama_metode')
                    ->required(),

                TextInput::make('kode_invoice')
                    ->label('Kode Invoice')
                    ->required()
                    ->placeholder('Contoh: 001')
                    ->maxLength(100),

            ]),

            Grid::make(3)->schema([
                TextInput::make('total_bayar')
                    ->label('Total Bayar')
                    ->numeric()
                    ->readOnly()
                    ->required()
                    ->default(fn(Get $get) => session('bayar_total') ?? 0),

                TextInput::make('dibayar')
                    ->label('Dibayar')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->debounce(500)
                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('kembalian', max(0, intval($state) - intval($get('total_bayar')))))
                    ->rules([
                        fn(Get $get) => function ($attribute, $value, $fail) use ($get) {
                            if (intval($value) < intval($get('total_bayar'))) {
                                $fail('Jumlah dibayar tidak boleh kurang dari total yang harus dibayar.');
                            }
                        }
                    ]),
            ]),
            Grid::make(1)->schema([
                TextInput::make('kembalian')
                    ->label('Kembalian')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->default(0),
            ]),

            Grid::make(1)->schema([
                Repeater::make('detail')
                    ->label('Rincian Pembayaran')
                    ->default(session('bayar_detail_items') ?? [])
                    ->disableItemCreation()
                    ->disableItemDeletion()
                    ->schema([
                        Hidden::make('jenis_item'),
                        Hidden::make('item_id'),
                        Hidden::make('nama_item'),

                        TextInput::make('jenis_item')
                            ->label('Jenis Item')
                            ->readOnly()
                            ->dehydrated(false)
                            ->columnSpan(2),

                        TextInput::make('nama_item')
                            ->label(fn(Get $get) => $get('jenis_item') === 'jasa' ? 'Nama Jasa' : 'Nama Sparepart')
                            ->readOnly()
                            ->columnSpan(4),

                        TextInput::make('qty')
                            ->label(fn(Get $get) => $get('jenis_item') === 'jasa' ? 'Jenis Asuransi' : 'Qty')
                            ->readOnly()
                            ->formatStateUsing(function ($state, Get $get) {
                                if ($get('jenis_item') === 'jasa') {
                                    $asuransi = Asuransi::find($state);
                                    return $asuransi ? $asuransi->nama : '-';
                                }
                                return $state;
                            })
                            ->dehydrated(false)
                            ->columnSpan(2),

                        TextInput::make('harga_satuan')
                            ->label(fn(Get $get) => $get('jenis_item') === 'jasa' ? 'Harga' : 'Harga Satuan')
                            ->numeric()
                            ->readOnly()
                            ->columnSpan(2),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(2),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function hitungTotal(Set $set, Get $get): void
    {
        $detailItems = session('bayar_detail_items') ?? [];
        $ppnJasa = 0;
        $ppnSparepart = 0;

        foreach ($detailItems as $item) {
            if ($item['jenis_item'] === 'jasa' && $get('gunakan_ppn_jasa')) {
                $ppnJasa += 0.11 * $item['subtotal'];
            }

            if ($item['jenis_item'] === 'sparepart' && $get('gunakan_ppn_sparepart')) {
                $ppnSparepart += 0.11 * $item['subtotal'];
            }
        }

        $baseTotal = intval(session('bayar_total') ?? 0);
        $totalBayar = $baseTotal + $ppnJasa + $ppnSparepart;

        $set('total_bayar', $totalBayar);

        $kembalian = intval($get('dibayar')) - $totalBayar;
        $set('kembalian', $kembalian > 0 ? $kembalian : 0);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metodePembayaran.nama_metode')->label('Metode'),
                Tables\Columns\TextColumn::make('total_bayar')->money('IDR'),
                Tables\Columns\TextColumn::make('dibayar')->money('IDR'),
                Tables\Columns\TextColumn::make('kembalian')->money('IDR'),
                Tables\Columns\TextColumn::make('detail_count')->label('Jumlah Item')->counts('detail'),
                Tables\Columns\BadgeColumn::make('status_pembayaran')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->dibayar >= $record->total_bayar ? 'Sudah Bayar' : 'Belum Bayar')
                    ->colors([
                        'success' => 'Sudah Bayar',
                        'danger' => 'Belum Bayar',
                    ]),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->id === 1 || $user->hasRole('admin') || $user->can('laporan pembayaran'));
    }
}
