<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asuransi;
use App\Models\MetodePembayaran;

class AsuransiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asuransi::create([
            'nama' => 'Pribadi',
        ]);

        MetodePembayaran::insert([
            [
                'nama_metode' => 'Cash',
                'status' => 'Aktif'
            ],
            [
                'nama_metode' => 'Transfer',
                'status' => 'Aktif'
            ],
        ]);
    }
}
