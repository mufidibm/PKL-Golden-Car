<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //// Membuat user admin
        $admin = User::firstOrCreate([
            'email' => 'admin@seeder.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        // Membuat user kasir
        $kasir = User::firstOrCreate([
            'email' => 'kasir@seeder.com',
        ], [
            'name' => 'Kasir',
            'password' => Hash::make('password'),
        ]);
        $kasir->assignRole('kasir');

        // Membuat user mekanik
        $mekanik = User::firstOrCreate([
            'email' => 'mekanik@seeder.com',
        ], [
            'name' => 'Mekanik',
            'password' => Hash::make('password'),
        ]);
        $mekanik->assignRole('mekanik');

        // Membuat user owner
        $owner = User::firstOrCreate([
            'email' => 'owner@seeder.com',
        ], [
            'name' => 'Owner',
            'password' => Hash::make('password'),
        ]);
        $owner->assignRole('owner');

    }
}
