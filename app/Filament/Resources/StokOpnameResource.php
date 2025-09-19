<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokOpnameResource\Pages;
use App\Filament\Resources\StokOpnameResource\RelationManagers;
use App\Models\StokOpname;
use App\Models\Barang;
use App\Models\Stok;
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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Manajemen Inventory';

    protected static ?string $label = 'Stok Opname';
    protected static ?string $pluralLabel = 'Stok Opname';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('jenis_inventory')
                    ->label('Jenis Inventory')
                    ->options([
                        'barang' => 'Sparepart',
                        'stok' => 'Stok Inventory',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Reset nilai saat jenis inventory berubah
                        $set('item_id', null);
                        $set('stok_lama', 0);
                        $set('item_info', '');
                        $set('satuan_info', '');
                        $set('selisih', 0);
                    }),

                Select::make('item_id')
                    ->label(function (callable $get) {
                        $jenis = $get('jenis_inventory');
                        return $jenis === 'barang' ? 'Pilih Sparepart' : 'Pilih Stok';
                    })
                    ->options(function (callable $get) {
                        $jenis = $get('jenis_inventory');
                        if ($jenis === 'barang') {
                            return Barang::pluck('nama_barang', 'id');
                        } elseif ($jenis === 'stok') {
                            return Stok::pluck('nama_stok', 'id');
                        }
                        return [];
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $jenis = $get('jenis_inventory');

                        if ($jenis === 'barang' && $state) {
                            $barang = Barang::find($state);
                            if ($barang) {
                                $set('stok_lama', $barang->stok);
                                $set('item_info', $barang->kode_barang . ' - ' . $barang->nama_barang);
                                $set('satuan_info', $barang->satuan);
                            }
                        } elseif ($jenis === 'stok' && $state) {
                            $stok = Stok::find($state);
                            if ($stok) {
                                $set('stok_lama', $stok->stok);
                                $set('item_info', $stok->kode_stok . ' - ' . $stok->nama_stok . ' (' . $stok->kategori . ')');
                                $set('satuan_info', $stok->satuan);
                            }
                        }

                        if (!$state) {
                            $set('stok_lama', 0);
                            $set('item_info', '');
                            $set('satuan_info', '');
                        }
                    }),

                TextInput::make('item_info')
                    ->label('Info Item')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Pilih item terlebih dahulu'),

                TextInput::make('satuan_info')
                    ->label('Satuan')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('stok_lama')
                    ->label('Stok Lama')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

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
                    ->label('Selisih')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->suffixIcon(function (callable $get) {
                        $selisih = (int) $get('selisih');
                        if ($selisih > 0) return 'heroicon-o-arrow-trending-up';
                        if ($selisih < 0) return 'heroicon-o-arrow-trending-down';
                        return 'heroicon-o-minus';
                    })
                    ->extraAttributes(function (callable $get) {
                        $selisih = (int) $get('selisih');
                        return [
                            'class' => match (true) {
                                $selisih > 0 => 'text-green-500',  // naik
                                $selisih < 0 => 'text-red-500',    // turun
                                default => 'text-gray-500',        // netral
                            },
                        ];
                    }),


                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3),

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
                TextColumn::make('jenis_inventory')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'barang' => 'info',
                        'stok' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'barang' => 'Sparepart',
                        'stok' => 'Stok Inventory',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('item_name')
                    ->label('Nama Item')
                    ->getStateUsing(function (StokOpname $record) {
                        if ($record->jenis_inventory === 'barang') {
                            $barang = Barang::find($record->item_id);
                            return $barang ? $barang->nama_barang : '-';
                        } elseif ($record->jenis_inventory === 'stok') {
                            $stok = Stok::find($record->item_id);
                            return $stok ? $stok->nama_stok : '-';
                        }
                        return '-';
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('item_code')
                    ->label('Kode')
                    ->getStateUsing(function (StokOpname $record) {
                        if ($record->jenis_inventory === 'barang') {
                            $barang = Barang::find($record->item_id);
                            return $barang ? $barang->kode_barang : '-';
                        } elseif ($record->jenis_inventory === 'stok') {
                            $stok = Stok::find($record->item_id);
                            return $stok ? $stok->kode_stok : '-';
                        }
                        return '-';
                    }),

                TextColumn::make('stok_lama')
                    ->label('Stok Lama')
                    ->alignCenter(),

                TextColumn::make('stok_baru')
                    ->label('Stok Baru')
                    ->alignCenter(),

                TextColumn::make('selisih')
                    ->label('Selisih')
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state > 0 => 'success',
                        $state < 0 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(function (int $state): string {
                        if ($state > 0) return '+' . $state;
                        return (string) $state;
                    })
                    ->alignCenter(),

                TextColumn::make('tanggal_opname')
                    ->label('Tanggal Opname')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_inventory')
                    ->label('Jenis Inventory')
                    ->options([
                        'barang' => 'Sparepart',
                        'stok' => 'Stok Inventory',
                    ]),

                Tables\Filters\Filter::make('selisih_positif')
                    ->label('Selisih Positif')
                    ->query(fn(Builder $query): Builder => $query->where('selisih', '>', 0)),

                Tables\Filters\Filter::make('selisih_negatif')
                    ->label('Selisih Negatif')
                    ->query(fn(Builder $query): Builder => $query->where('selisih', '<', 0)),
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
            ->defaultSort('tanggal_opname', 'desc');
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
            'view' => Pages\ViewStokOpname::route('/{record}'),
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
