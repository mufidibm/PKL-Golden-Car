<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Pegawai;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
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
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pegawai_id')
                    ->label('Pegawai')
                    ->relationship('pegawai', 'nama') // pastikan relasi benar
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $pegawai = Pegawai::find($state);

                        if ($pegawai) {
                            $set('name', $pegawai->nama);
                            $set('email', $pegawai->email); // ambil dari pegawai
                        }
                    }),

                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->readOnly()
                    ->dehydrated(), // ✅ pastikan tetap dikirim ke backend

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->readOnly()
                    ->dehydrated(), // ✅ tetap dikirim

                TextInput::make('password')
                    ->password()
                    ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->label('Password')
                    ->minLength(6),


                Select::make('role')
                    ->label('Role')
                    ->options(\Spatie\Permission\Models\Role::pluck('name', 'name'))
                    ->default(fn($record) => $record?->roles?->pluck('name')->first())
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $role = \Spatie\Permission\Models\Role::where('name', $state)->first();
                        if ($role) {
                            $set('permissions', $role->permissions->pluck('name')->toArray());
                        } else {
                            $set('permissions', []);
                        }
                    }),

                CheckboxList::make('permissions')
                    ->label('Permission Tambahan')
                    ->options(\Spatie\Permission\Models\Permission::pluck('name', 'name'))
                    ->default(fn($record) => $record?->permissions?->pluck('name')->toArray())
                    ->columns(2)
                    ->searchable(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pegawai.jabatan.nama_jabatan')->label('Jabatan'),
                TextColumn::make('pegawai.departemen.nama_departemen')->label('Departemen'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->colors(['success']),
            ])
            ->filters([
                SelectFilter::make('pegawai.jabatan_id')
                    ->label('Jabatan')
                    ->relationship('pegawai.jabatan', 'nama')
                    ->searchable(),

                SelectFilter::make('pegawai.departemen_id')
                    ->label('Departemen')
                    ->relationship('pegawai.departemen', 'nama')
                    ->searchable(),

                SelectFilter::make('pegawai.status_kepegawaian')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak' => 'Tidak Aktif',
                    ]),

                SelectFilter::make('pegawai.jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                Filter::make('tanggal_masuk')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('to')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereHas('pegawai', fn($q2) => $q2->whereDate('tanggal_masuk', '>=', $data['from'])))
                            ->when($data['to'], fn($q) => $q->whereHas('pegawai', fn($q2) => $q2->whereDate('tanggal_masuk', '<=', $data['to'])));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('atur_roles')
                    ->label('Role & Akses')
                    ->icon('heroicon-o-lock-closed')
                    ->modalHeading('Atur Role & Akses Pengguna')
                    ->form([
                        Select::make('role')
                            ->label('Role')
                            ->options(\Spatie\Permission\Models\Role::pluck('name', 'name'))
                            ->required(),

                        // Jika kamu ingin centang permission langsung:
                        CheckboxList::make('permissions')
                            ->label('Permission Tambahan')
                            ->options(\Spatie\Permission\Models\Permission::pluck('name', 'name'))
                            ->columns(2),
                    ])
                    ->action(function ($record, array $data) {
                        $record->syncRoles($data['role']);

                        // Jika mau aktifkan permission langsung juga:
                        $record->syncPermissions($data['permissions'] ?? []);
                    }),

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

    public static function getEloquentQuery(): Builder
    {
        // return parent::getEloquentQuery()->with([
        //     'pegawai.jabatan',
        //     'pegawai.departemen',
        // ]);
        return parent::getEloquentQuery()
            ->with(['pegawai.jabatan', 'pegawai.departemen', 'roles', 'permissions']);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user && $user->id === 1) return true;
        return $user && ($user->hasRole('admin') || $user->can('manajemen user'));
    }
}
