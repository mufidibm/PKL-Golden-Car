<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokOpnameResource\Pages;
use App\Filament\Resources\StokOpnameResource\RelationManagers;
use App\Models\StokOpname;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StokOpnameResource extends Resource
{
    protected static ?string $model = StokOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Inventory';

    protected static ?string $label = 'Stok Opname';
    protected static ?string $pluralLabel = 'Stok Opname';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('barang_id')
                ->label('Barang')
                ->relationship('barang', 'nama_barang')
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    // Ambil stok lama dari relasi barang saat dipilih
                    $barang = \App\Models\Barang::find($state);
                    if ($barang) {
                        $set('stok_lama', $barang->stok);
                    } else {
                        $set('stok_lama', 0);
                    }
                }),

            TextInput::make('stok_lama')
                ->label('Stok Lama')
                ->numeric()
                ->disabled()
                ->dehydrated(), // penting agar nilainya tetap tersimpan

            TextInput::make('stok_baru')
                ->label('Stok Baru')
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $stokLama = (int) $get('stok_lama');
                    $stokBaru = (int) $state;
                    $selisih = $stokBaru - $stokLama;
                    $set('selisih', $selisih);
                }),

            TextInput::make('selisih')
                ->numeric()
                ->disabled()
                ->dehydrated(), // agar ikut tersimpan

            Textarea::make('keterangan')
                ->label('Keterangan'),

            DatePicker::make('tanggal_opname')
                ->label('Tanggal Opname')
                ->default(now())
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('barang.nama_barang')
                ->label('Nama Barang')
                ->searchable()
                ->sortable(),

            TextColumn::make('stok_lama')
                ->label('Stok Lama'),

            TextColumn::make('stok_baru')
                ->label('Stok Baru'),

            TextColumn::make('selisih')
                ->label('Selisih'),

            TextColumn::make('tanggal_opname')
                ->label('Tanggal Opname')
                ->date('d M Y'),
            ])
            ->filters([
                 Tables\Filters\SelectFilter::make('barang_id')
                ->label('Filter Barang')
                ->relationship('barang', 'nama_barang'),
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
            'index' => Pages\ListStokOpnames::route('/'),
            'create' => Pages\CreateStokOpname::route('/create'),
            'edit' => Pages\EditStokOpname::route('/{record}/edit'),
        ];
    }    

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('manajemen inventory'));
    }
}
