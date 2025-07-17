<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record;

        $data['role'] = $user->roles()->pluck('name')->first();
        $data['permissions'] = $user->permissions()->pluck('name')->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['role'], $data['permissions']);
        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncRoles([$this->form->getState()['role']]);
        $this->record->syncPermissions($this->form->getState()['permissions'] ?? []);
    }
}
