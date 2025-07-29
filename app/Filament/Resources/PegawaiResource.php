<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Kepegawaian';

    protected static ?string $label = 'Master Pegawai';
    protected static ?string $pluralLabel = 'Master Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nip')->required()->unique(ignoreRecord: true),
                TextInput::make('nama')->required(),
                DatePicker::make('tanggal_lahir')->required(),
                Select::make('jenis_kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])->required(),
                DatePicker::make('tanggal_masuk')->required(),
                Textarea::make('alamat'),
                TextInput::make('no_hp'),
                TextInput::make('email')->email(),
                Select::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak' => 'Tidak Aktif',
                    ])
                    ->default('aktif')
                    ->required(),

                Select::make('departemen_id')
                    ->label('Departemen')
                    ->relationship('departemen', 'nama_departemen')
                    ->createOptionForm([
                        TextInput::make('nama_departemen')
                            ->label('Nama Departemen')
                            ->required(),

                        TextInput::make('deskripsi')
                            ->label('Deskripsi')
                            ->nullable(),
                    ])
                    ->searchable()
                    ->preload(),
                Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama_jabatan')
                    ->createOptionForm([
                        TextInput::make('nama_jabatan')
                            ->label('Nama Jabatan')
                            ->required(),

                        TextInput::make('deskripsi')
                            ->label('Deskripsi')
                            ->nullable(),
                    ])
                    ->searchable()
                    ->preload()
                    ->required()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')->sortable()->searchable(),
                TextColumn::make('nama')->sortable()->searchable(),
                TextColumn::make('jenis_kelamin'),
                TextColumn::make('tanggal_masuk')->date('d M Y'),
                TextColumn::make('departemen.nama_departemen')->label('Departemen'),
                TextColumn::make('jabatan.nama_jabatan')->label('Jabatan'),
                TextColumn::make('status_kepegawaian')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak' => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('jenis_kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
                SelectFilter::make('departemen_id')->relationship('departemen', 'nama_departemen'),
                SelectFilter::make('jabatan_id')->relationship('jabatan', 'nama_jabatan'),
                SelectFilter::make('status_kepegawaian')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak' => 'Tidak Aktif',
                    ]),

                Filter::make('tanggal_masuk')->form([
                    DatePicker::make('from'),
                    DatePicker::make('until'),
                ])->query(function ($query, $data) {
                    return $query
                        ->when($data['from'], fn($q, $date) => $q->whereDate('tanggal_masuk', '>=', $date))
                        ->when($data['until'], fn($q, $date) => $q->whereDate('tanggal_masuk', '<=', $date));
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('kepegawaian'));
    }
}
