<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
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

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Inventory';

    protected static ?string $label = 'Master Barang';
    protected static ?string $pluralLabel = 'Master Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_barang')->required()->unique(ignoreRecord: true),
                TextInput::make('nama_barang')->required(),
                Select::make('kategori')
                    ->options([
                        'Sparepart' => 'Sparepart',
                        'Jasa' => 'Jasa',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                TextInput::make('satuan')->required(),
                TextInput::make('stok')->numeric()->default(0),
                TextInput::make('harga_beli')->numeric()->required(),
                TextInput::make('harga_jual')->numeric()->required(),
                Textarea::make('keterangan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->searchable()->sortable(),
                TextColumn::make('nama_barang')->searchable()->sortable(),
                TextColumn::make('kategori')->sortable(),
                TextColumn::make('stok')->sortable(),
                TextColumn::make('harga_jual')->money('IDR')->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->options([
                        'Sparepart' => 'Sparepart',
                        'Jasa' => 'Jasa',
                        'Lainnya' => 'Lainnya',
                    ])
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
            'index' => Pages\ListBarangs::route('/'),
            //'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('manajemen inventory'));
    }
}
