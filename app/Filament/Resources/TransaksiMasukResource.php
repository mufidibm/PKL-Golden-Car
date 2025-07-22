<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiMasukResource\Pages;
use App\Filament\Resources\TransaksiMasukResource\RelationManagers;
use App\Models\TransaksiMasuk;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiMasukResource extends Resource
{
    protected static ?string $model = TransaksiMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Transaksi';
    protected static ?string $pluralLabel = 'Transaksi';

    public static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kendaraan_id')
                    ->label('Nomor Polisi Kendaraan')
                    ->relationship('kendaraan', 'no_polisi')
                    ->required()
                    ->searchable(),

                Select::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'sedang dikerjakan' => 'Sedang Dikerjakan',
                        'menunggu sparepart' => 'Menunggu Sparepart',
                        'pemeriksaan akhir' => 'Pemeriksaan Akhir',
                        'selesai' => 'Selesai',
                    ])
                    ->default('menunggu'),

                TextInput::make('keluhan')
                    ->label('Keluhan')
                    ->columnSpanFull(),

                DatePicker::make('waktu_masuk')
                    ->label('Tanggal Masuk')
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kendaraan.customer.nama')->label('Customer'),
                TextColumn::make('kendaraan.no_polisi')->label('No Polisi'),
                TextColumn::make('status')->badge()->color(fn(string $state): string => match ($state) {
                    'menunggu' => 'gray',
                    'sedang dikerjakan' => 'warning',
                    'menunggu sparepart' => 'danger',
                    'pemeriksaan akhir' => 'info',
                    'selesai' => 'success',
                }),
                TextColumn::make('status_pembayaran')
                ->label('Pembayaran')
                ->getStateUsing(function ($record) {
                    return $record->pembayaran ? 'Sudah Bayar' : 'Belum Bayar';
                })
                ->badge()
                ->color(fn ($state) => $state === 'Sudah Bayar' ? 'success' : 'danger'),
                TextColumn::make('waktu_masuk')->label('Masuk')->since(),
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
            \App\Filament\Resources\TransaksiMasukResource\RelationManagers\PengerjaanServisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksiMasuks::route('/'),
            'create' => Pages\CreateTransaksiMasuk::route('/create'),
            'edit' => Pages\EditTransaksiMasuk::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['kendaraan.customer', 'paketServis']);
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('transaksi'));
    }
}
