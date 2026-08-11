<?php

namespace Database\Seeders;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengembalianSeeder extends Seeder
{
    public function run(): void
    {
        $peminjaman = Peminjaman::whereHas('anggota', function ($query) {
            $query->where('nomor_anggota', 'AGT001');
        })
        ->where('status', 'dikembalikan')
        ->firstOrFail();

        $petugas = User::where(
            'email',
            'petugas@perpustakaan.test'
        )->firstOrFail();

        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'petugas_id' => $petugas->id,
            'tanggal_pengembalian' => '2026-08-07',
            'denda' => 0,
        ]);
    }
}