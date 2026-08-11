<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $ryan = Anggota::where(
            'nomor_anggota',
            'AGT001'
        )->firstOrFail();

        $budi = Anggota::where(
            'nomor_anggota',
            'AGT002'
        )->firstOrFail();

        $siti = Anggota::where(
            'nomor_anggota',
            'AGT003'
        )->firstOrFail();

        $bukuLaravel = Buku::where(
            'isbn',
            '9786020000001'
        )->firstOrFail();

        $bukuPostgreSQL = Buku::where(
            'isbn',
            '9786020000002'
        )->firstOrFail();

        $bukuJaringan = Buku::where(
            'isbn',
            '9786020000003'
        )->firstOrFail();

        $petugas = User::where(
            'email',
            'petugas@perpustakaan.test'
        )->firstOrFail();

        Peminjaman::create([
            'anggota_id' => $ryan->id,
            'buku_id' => $bukuLaravel->id,
            'petugas_id' => $petugas->id,
            'tanggal_pinjam' => '2026-08-01',
            'tanggal_jatuh_tempo' => '2026-08-08',
            'status' => 'dikembalikan',
        ]);

        Peminjaman::create([
            'anggota_id' => $budi->id,
            'buku_id' => $bukuPostgreSQL->id,
            'petugas_id' => $petugas->id,
            'tanggal_pinjam' => '2026-08-05',
            'tanggal_jatuh_tempo' => '2026-08-12',
            'status' => 'dipinjam',
        ]);

        Peminjaman::create([
            'anggota_id' => $siti->id,
            'buku_id' => $bukuJaringan->id,
            'petugas_id' => $petugas->id,
            'tanggal_pinjam' => '2026-08-10',
            'tanggal_jatuh_tempo' => '2026-08-17',
            'status' => 'dipinjam',
        ]);
    }
}