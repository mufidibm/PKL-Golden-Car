<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Pelanggan';

    protected static ?string $label = 'Master Customer';
    protected static ?string $pluralLabel = 'Master Customer';

    public static ?int $navigationSort = -100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required(),
                Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])->required(),
                TextInput::make('no_hp')->required(),
                Textarea::make('alamat')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama'),
                TextColumn::make('jenis_kelamin'),
                TextColumn::make('no_hp'),
                TextColumn::make('alamat'),
            ])
            ->filters([
                SelectFilter::make('nama')
                    ->label('nama')
                    ->searchable()
                    ->options(
                        fn() => \App\Models\Customer::query()
                            ->distinct()
                            ->pluck('nama', 'nama')
                    ),
                SelectFilter::make('jenis_kelamin')
                    ->label('jenis_kelamin')
                    ->searchable()
                    ->options(
                        fn() => \App\Models\Customer::query()
                            ->distinct()
                            ->pluck('jenis_kelamin', 'jenis_kelamin')
                    ),

                SelectFilter::make('no_hp')
                    ->label('no_hp')
                    ->searchable()
                    ->options(
                        fn() => \App\Models\Customer::query()
                            ->distinct()
                            ->pluck('no_hp', 'no_hp')
                    ),
                SelectFilter::make('alamat')
                    ->label('alamat')
                    ->searchable()
                    ->options(
                        fn() => \App\Models\Customer::query()
                            ->distinct()
                            ->pluck('alamat', 'alamat')
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
            'index' => Pages\ListCustomers::route('/'),
            //'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return CustomerResource::getUrl(); // kembali ke halaman list Customers
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('master pelanggan'));
    }
}
