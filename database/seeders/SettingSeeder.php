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
                'nama_bengkel' => 'Golden Car',
                'alamat'       => 'Jl. Raya Jati Mekar No.24, Bekasi',
                'telepon'      => '0813 8390 2292',
                'email'        => 'admin@golden-car.co.id',
                'logo'         => null, 
            ]);
        }
    }
}
