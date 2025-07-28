<?php

namespace App\Filament\Widgets;

use App\Models\Asuransi;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;

class AsuransiTableWidget extends BaseWidget
{
    protected static ?int $sort = -1;

    public function table(Table $table): Table
    {
        return $table
            ->query(Asuransi::query())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('nama')->label('Nama Asuransi'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Asuransi::class)
                    ->form(fn(Form $form) => $form->schema([
                        Forms\Components\TextInput::make('nama')->required()->label('Nama Asuransi'),
                    ])),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(fn(Form $form) => $form->schema([
                        Forms\Components\TextInput::make('nama')->required()->label('Nama Asuransi'),
                    ])),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function canView(): bool
    {
        return true;
    }
}
