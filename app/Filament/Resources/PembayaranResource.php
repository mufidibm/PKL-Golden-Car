<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
        return $form
            ->schema([
                Hidden::make('id_transaksi_masuk')
                    ->default(fn() => session('bayar_transaksi_id'))
                    ->required(),

                Select::make('metode_pembayaran_id')
                    ->label('Metode Pembayaran')
                    ->relationship('metodePembayaran', 'nama_metode')
                    ->required(),

                TextInput::make('total_bayar')
                    ->label('Total Bayar')
                    ->numeric()
                    ->required()
                    ->default(fn() => session('bayar_total') ?? 0),

                TextInput::make('dibayar')
                    ->label('Dibayar')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $kembalian = intval($state) - intval($get('total_bayar'));
                        $set('kembalian', $kembalian > 0 ? $kembalian : 0);
                    }),


                TextInput::make('kembalian')
                    ->label('Kembalian')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->default(0),

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
                    ->columns(12) // tampil horizontal tapi tetap dalam satu grid
                    ->columnSpanFull(),
                // Total kolom disesuaikan dengan columnSpan


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
                ->getStateUsing(function ($record) {
                    return $record->dibayar >= $record->total_bayar ? 'Sudah Bayar' : 'Belum Bayar';
                })
                ->colors([
                    'success' => 'Sudah Bayar',
                    'danger' => 'Belum Bayar',
                ])

            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
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
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('laporan pembayaran'));
    }
}
