<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Ambil role sebelum create user
        $role = $data['role'] ?? null;
        $permissions = $data['permissions'] ?? [];
        
        // Hapus role dan permissions dari data sebelum create
        unset($data['role'], $data['permissions']);
        
        // Create user
        $user = static::getModel()::create($data);
        
        // Assign role dan permissions setelah user dibuat
        if ($role) {
            $user->assignRole($role);
        }
        
        if (!empty($permissions)) {
            $user->givePermissionTo($permissions);
        }
        
        return $user;
    }
}