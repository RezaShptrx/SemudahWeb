<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@semudah.local'],
            [
                'name' => 'Admin SEMUDAH',
                'password' => Hash::make('password'),
                'phone' => '6281200000001',
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // 2. Petugas Jaga
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@semudah.local'],
            [
                'name' => 'Petugas Jaga',
                'password' => Hash::make('password'),
                'phone' => '6281200000002',
                'role' => 'petugas',
                'is_active' => true,
            ]
        );
        $petugas->assignRole('petugas');
    }
}
