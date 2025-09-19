<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokResource\Pages;
use App\Filament\Resources\StokResource\RelationManagers;
use App\Models\Stok;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StokResource extends Resource
{
    protected static ?string $model = Stok::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Manajemen Inventory';

    protected static ?string $label = 'Master Stok';
    protected static ?string $pluralLabel = 'Master Stok';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_stok')
                    ->label('Kode Barang')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                TextInput::make('nama_stok')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255),
                TextInput::make('stok')
                    ->label('Jumlah Stok')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Bahan Paint' => 'Bahan Paint',
                        'Bahan non Paint' => 'Bahan non Paint',
                        'Tools' => 'Tools',
                    ])
                    ->required()
                    ->searchable(),
                TextInput::make('harga_beli')
                    ->label('Harga Beli')
                    ->numeric()
                    ->required()
                    ->prefix('Rp'),
                TextInput::make('satuan')
                    ->label('Satuan')
                    ->required()
                    ->maxLength(20)
                    ->placeholder('Contoh: pcs, kg, liter'),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->nullable()
                    ->maxLength(500)
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_stok')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('nama_stok')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('stok')
                    ->label('Jumlah')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state == 0 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Bahan Paint' => 'info',
                        'Bahan non Paint' => 'warning',
                        'Tools' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('harga_beli')
                    ->label('Harga Beli')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Bahan Paint' => 'Bahan Paint',
                        'Bahan non Paint' => 'Bahan non Paint',
                        'Tools' => 'Tools',
                    ]),
                SelectFilter::make('stok_status')
                    ->label('Status Stok')
                    ->options([
                        'habis' => 'Habis (0)',
                        'sedikit' => 'Sedikit (≤ 10)',
                        'aman' => 'Aman (> 10)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'habis',
                            fn (Builder $query): Builder => $query->where('stok', 0),
                        )->when(
                            $data['value'] === 'sedikit',
                            fn (Builder $query): Builder => $query->where('stok', '>', 0)->where('stok', '<=', 10),
                        )->when(
                            $data['value'] === 'aman',
                            fn (Builder $query): Builder => $query->where('stok', '>', 10),
                        );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('nama_stok');
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
            'index' => Pages\ListStoks::route('/'),
            'create' => Pages\CreateStok::route('/create'),
            'view' => Pages\ViewStok::route('/{record}'),
            'edit' => Pages\EditStok::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('manajemen inventory'));
    }
}