<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartemenResource\Pages;
use App\Filament\Resources\DepartemenResource\RelationManagers;
use App\Models\Departemen;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartemenResource extends Resource
{
    protected static ?string $model = Departemen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Kepegawaian';

    protected static ?string $label = 'Master Departemen';
    protected static ?string $pluralLabel = 'Master Departemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_departemen')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Nama Departemen'),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->placeholder('Opsional')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_departemen')->searchable()->sortable(),
                TextColumn::make('deskripsi')->limit(50)->wrap(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y'),
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
            'index' => Pages\ListDepartemens::route('/'),
            //'create' => Pages\CreateDepartemen::route('/create'),
            'edit' => Pages\EditDepartemen::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('kepegawaian'));
    }
}
