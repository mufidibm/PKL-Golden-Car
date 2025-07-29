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
                'alamat'       => 'Jl. Raya Jati Mekar RT 001/RW 012 N0 24 Jati Asih, Bekasi',
                'telepon'      => '085353112098',
                'email'        => 'admin@golden-car.co.id',
                'rekening'     => '1670009743047',
                'logo'         => null, 
            ]);
        }
    }
}
