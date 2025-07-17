<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $kasir = Role::firstOrCreate(['name' => 'kasir']);
        $mekanik = Role::firstOrCreate(['name' => 'mekanik']);
        $owner = Role::firstOrCreate(['name' => 'owner']);

        $permissions = [
            'dashboard',
            'laporan pembayaran',
            'laporan pendapatan',
            'transaksi',
            'master pelanggan',
            'manajemen inventory',
            'kepegawaian',
            'master data keuangan',
            'service manajemen',
            'manajemen user',
            'lainnya',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        $admin->givePermissionTo(Permission::all());
        $kasir->givePermissionTo(['transaksi', 'laporan pembayaran', 'laporan pendapatan', 'master pelanggan']);
        $mekanik->givePermissionTo(['service manajemen']);
        $owner->givePermissionTo(['dashboard', 'laporan pembayaran', 'laporan pendapatan', 'master data keuangan']);
    }
}
