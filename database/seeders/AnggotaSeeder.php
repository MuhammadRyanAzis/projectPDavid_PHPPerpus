<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        $ryan = User::where(
            'email',
            'ryan@perpustakaan.test'
        )->firstOrFail();

        $budi = User::where(
            'email',
            'budi@perpustakaan.test'
        )->firstOrFail();

        $siti = User::where(
            'email',
            'siti@perpustakaan.test'
        )->firstOrFail();

        Anggota::create([
            'user_id' => $ryan->id,
            'nomor_anggota' => 'AGT001',
            'alamat' => 'Malang',
            'status' => 'aktif',
        ]);

        Anggota::create([
            'user_id' => $budi->id,
            'nomor_anggota' => 'AGT002',
            'alamat' => 'Malang',
            'status' => 'aktif',
        ]);

        Anggota::create([
            'user_id' => $siti->id,
            'nomor_anggota' => 'AGT003',
            'alamat' => 'Pasuruan',
            'status' => 'aktif',
        ]);
    }
}