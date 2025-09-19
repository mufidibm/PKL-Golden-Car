<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KendaraanResource\Pages;
use App\Filament\Resources\KendaraanResource\RelationManagers;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Pelanggan';

    protected static ?string $label = 'Master Kendaraan';
    protected static ?string $pluralLabel = 'Master Kendaraan';

    public static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'nama') 
                    ->searchable()
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama')->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('no_hp')->required(),
                        Forms\Components\Textarea::make('alamat'),
                    ]),

                TextInput::make('no_polisi')->required()->unique(ignoreRecord: true),
                TextInput::make('tipe')->required(),
                TextInput::make('merek')->required(),
                TextInput::make('tahun')->numeric()->minValue(1980)->maxValue(date('Y'))->required(),
                TextInput::make('warna')->required(),
                TextInput::make('jenis_kendaraan')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.nama')->label('Customer'),
                TextColumn::make('no_polisi'),
                TextColumn::make('merek'),
                TextColumn::make('tipe'),
                TextColumn::make('tahun'),
            ])
            ->filters([
                SelectFilter::make('customer_id')
                    ->label('Customer')
                    ->searchable()
                    ->relationship('customer', 'nama'),

                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->searchable()
                    ->options(
                        fn() => \App\Models\Kendaraan::query()
                            ->distinct()
                            ->pluck('tahun', 'tahun')
                    ),

                SelectFilter::make('merek')
                    ->label('Merek')
                    ->searchable()
                    ->options(
                        fn() => \App\Models\Kendaraan::query()
                            ->distinct()
                            ->pluck('merek', 'merek')
                    ),
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
            'index' => Pages\ListKendaraans::route('/'),
            //'create' => Pages\CreateKendaraan::route('/create'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('manajemen inventory'));
    }
}
