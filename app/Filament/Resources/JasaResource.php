<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JasaResource\Pages;
use App\Models\Jasa;
use App\Models\Asuransi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Widgets\AsuransiTableWidget;

class JasaResource extends Resource
{
    protected static ?string $model = Jasa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Master Jasa';
    protected static ?string $navigationGroup = 'Manajemen Inventory';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jasa')
                    ->schema([
                        Forms\Components\TextInput::make('nama_jasa')
                            ->label('Nama Jasa')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Service AC, Cuci Mobil')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('asuransi_id')
                            ->label('Asuransi')
                            ->required()
                            ->relationship('asuransi', 'nama')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama')->required(),
                            ])
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('harga')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->placeholder('100000')
                            ->helperText('Masukkan harga dalam Rupiah')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_jasa')
                    ->label('Nama Jasa')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('asuransi.nama')
                    ->label('Asuransi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('asuransi_id')
                    ->label('Filter Asuransi')
                    ->options(fn() => \App\Models\Asuransi::pluck('nama', 'id')->toArray())
                    ->searchable()
                    ->preload(),
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
            ->emptyStateHeading('Belum ada data jasa')
            ->emptyStateDescription('Klik tombol "Tambah Jasa" untuk menambahkan jasa pertama.')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJasas::route('/'),
            'create' => Pages\CreateJasa::route('/create'),
            'edit' => Pages\EditJasa::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('manajemen inventory'));
    }
}
