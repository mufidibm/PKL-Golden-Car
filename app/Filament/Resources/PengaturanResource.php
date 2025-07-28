<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaturanResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;



class PengaturanResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 100;

    protected static ?string $navigationGroup = 'Lainnya';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama_bengkel')->required(),
            TextInput::make('alamat')->required(),
            TextInput::make('telepon'),
            TextInput::make('email')->email(),
            TextInput::make('rekening'),

            SpatieMediaLibraryFileUpload::make('logo')
                ->collection('logo')
                ->disk('public')
                ->directory('logo') 
                ->image()
                ->preserveFilenames()
                ->imageEditor()
                ->loadingIndicatorPosition('left')
                ->panelAspectRatio('1:1'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_bengkel')->label('Nama Bengkel'),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat'),
                Tables\Columns\TextColumn::make('telepon')->label('Telepon'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('rekening')->label('No Rekening'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]) // Nonaktifkan bulk action
            ->emptyStateActions([]); // Nonaktifkan create saat kosong
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaturans::route('/'),
            'edit' => Pages\EditPengaturan::route('/{record}/edit'),
            // 'create' dihapus agar tidak bisa diakses
        ];
    }

    // ⛔ Nonaktifkan tombol Create
    public static function canCreate(): bool
    {
        return false;
    }

    // ⛔ Nonaktifkan tombol Delete
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('lainnya'));
    }
}
