<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         
        if (Setting::count() == 0) {
            Setting::create([
                'nama_bengkel' => 'Bengkel Maju Jaya',
                'alamat'       => 'Jl. Mekar No. 123, Surabaya',
                'telepon'      => '081234567890',
                'email'        => 'info@bengkelmaju.com',
                'logo'         => null, 
            ]);
        }
    }
}
