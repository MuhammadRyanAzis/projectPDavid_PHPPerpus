<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@perpustakaan.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        User::create([
            'name' => 'Ryan Azis',
            'email' => 'ryan@perpustakaan.test',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@perpustakaan.test',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@perpustakaan.test',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);
    }
}