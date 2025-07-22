<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\{Grid, Hidden, Repeater, Select, TextInput};
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 99;
    protected static ?string $navigationGroup = 'Lainnya';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Hidden::make('id_transaksi_masuk')
                        ->default(fn() => session('bayar_transaksi_id'))
                        ->required(),

                    Select::make('metode_pembayaran_id')
                        ->label('Metode Pembayaran')
                        ->relationship('metodePembayaran', 'nama_metode')
                        ->required(),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('biaya_jasa')
                        ->label('Biaya Jasa Tambahan')
                        ->numeric()
                        ->default(0)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $baseTotal = intval(session('bayar_total')) ?? 0;
                            $totalBaru = $baseTotal + intval($state);
                            $set('total_bayar', $totalBaru);

                            $kembalian = intval($get('dibayar')) - $totalBaru;
                            $set('kembalian', $kembalian > 0 ? $kembalian : 0);
                        }),

                    TextInput::make('total_bayar')
                        ->label('Total Bayar')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->default(fn() => session('bayar_total') ?? 0),

                    TextInput::make('dibayar')
                        ->label('Dibayar')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $kembalian = intval($state) - intval($get('total_bayar'));
                            $set('kembalian', $kembalian > 0 ? $kembalian : 0);
                        })
                        ->rules([
                            function (Get $get) {
                                return function ($attribute, $value, $fail) use ($get) {
                                    if ($value < $get('total_bayar')) {
                                        $fail("Jumlah yang dibayar tidak boleh kurang dari total harus bayar.");
                                    }
                                };
                            },
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

                Repeater::make('detail')
                    ->label('Rincian Pembayaran')
                    ->default(session('bayar_detail_items') ?? [])
                    ->disableItemCreation()
                    ->schema([
                        Select::make('jenis_item')
                            ->label('Jenis Item')
                            ->options(['servis' => 'Jasa Servis', 'sparepart' => 'Sparepart'])
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('item_id')
                            ->label('ID Item')
                            ->required()
                            ->numeric()
                            ->columnSpan(2)
                            ->readOnly(),

                        TextInput::make('nama_item')
                            ->label('Nama Item')
                            ->required()
                            ->columnSpan(4)
                            ->readOnly(),

                        TextInput::make('qty')
                            ->label('Qty')
                            ->required()
                            ->numeric()
                            ->columnSpan(2)
                            ->readOnly(),

                        TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->required()
                            ->numeric()
                            ->columnSpan(2)
                            ->readOnly(),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->required()
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(2),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),
            ]);
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
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
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
