<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengerjaanSparepartResource\Pages;
use App\Filament\Resources\PengerjaanSparepartResource\RelationManagers;
use App\Models\PengerjaanSparepart;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengerjaanSparepartResource extends Resource
{
    protected static ?string $model = PengerjaanSparepart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Service Manajemen';

    protected static ?string $label = 'Pengerjaan Sparepart';
    protected static ?string $pluralLabel = 'Pengerjaan Sparepart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pengerjaan_servis_id')
                    ->relationship('pengerjaan', 'id')
                    ->searchable()
                    ->required(),

                Select::make('barang_id')
                    ->relationship('barang', 'nama_barang')
                    ->required()
                    ->reactive(),

                TextInput::make('qty')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->label(
                        fn(callable $get) =>
                        $get('barang_id')
                            ? 'Qty (Stok: ' . \App\Models\Barang::find($get('barang_id'))?->stok . ')'
                            : 'Qty'
                    )
                    ->rule(function (callable $get) {
                        $barang = \App\Models\Barang::find($get('barang_id'));
                        return $barang
                            ? 'max:' . $barang->stok
                            : null;
                    })
                    ->afterStateUpdated(
                        fn($state, callable $set, callable $get) =>
                        $set('subtotal', $state * $get('harga'))
                    ),


                TextInput::make('harga')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(
                        fn($state, callable $set, callable $get) =>
                        $set('subtotal', $get('qty') * $state)
                    ),

                TextInput::make('subtotal')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('barang.nama_barang')
                    ->label('Sparepart')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('qty')
                    ->label('Qty'),

                TextColumn::make('harga')
                    ->money('IDR', true)
                    ->label('Harga'),

                TextColumn::make('subtotal')
                    ->money('IDR', true)
                    ->label('Subtotal'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('barang_id')
                    ->label('Sparepart')
                    ->relationship('barang', 'nama_barang'),


                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
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
            'index' => Pages\ListPengerjaanSpareparts::route('/'),
            'create' => Pages\CreatePengerjaanSparepart::route('/create'),
            'edit' => Pages\EditPengerjaanSparepart::route('/{record}/edit'),
        ];
    }
}
