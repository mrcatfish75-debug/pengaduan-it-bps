<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ================================
        // ADMIN
        // ================================
        User::updateOrCreate(
            ['email' => 'admin@bps.go.id'],
            [
                'name' => 'Admin IT',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // ================================
        // KASUBAG
        // ================================
        User::updateOrCreate(
            ['email' => 'kasubag@bps.go.id'],
            [
                'name' => 'Kasubag',
                'password' => Hash::make('password'),
                'role' => 'kasubag',
            ]
        );

        // ================================
        // PEGAWAI
        // ================================
        User::updateOrCreate(
            ['email' => 'pegawai@bps.go.id'],
            [
                'name' => 'Pegawai',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
            ]
        );
    }
}