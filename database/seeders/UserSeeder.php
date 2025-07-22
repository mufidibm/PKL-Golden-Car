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
            'email' => 'admin@app.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('12345678'),
        ]);
        $admin->assignRole('Admin');

        // // Membuat user kasir
        // $kasir = User::firstOrCreate([
        //     'email' => 'kasir@app.com',
        // ], [
        //     'name' => 'Kasir',
        //     'password' => Hash::make('12345678'),
        // ]);
        // $kasir->assignRole('kasir');

        // // Membuat user mekanik
        // $mekanik = User::firstOrCreate([
        //     'email' => 'mekanik@app.com',
        // ], [
        //     'name' => 'Mekanik',
        //     'password' => Hash::make('12345678'),
        // ]);
        // $mekanik->assignRole('mekanik');

        // // Membuat user owner
        // $owner = User::firstOrCreate([
        //     'email' => 'owner@app.com',
        // ], [
        //     'name' => 'Owner',
        //     'password' => Hash::make('12345678'),
        // ]);
        // $owner->assignRole('owner');

    }
}
