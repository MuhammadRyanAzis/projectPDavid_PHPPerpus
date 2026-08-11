<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $pemrograman = Kategori::where(
            'nama_kategori',
            'Pemrograman'
        )->firstOrFail();

        $database = Kategori::where(
            'nama_kategori',
            'Database'
        )->firstOrFail();

        $jaringan = Kategori::where(
            'nama_kategori',
            'Jaringan'
        )->firstOrFail();

        $teknologi = Kategori::where(
            'nama_kategori',
            'Teknologi'
        )->firstOrFail();

        $web = Kategori::where(
            'nama_kategori',
            'Web Development'
        )->firstOrFail();

        Buku::create([
            'kategori_id' => $pemrograman->id,
            'judul' => 'Belajar Laravel untuk Pemula',
            'isbn' => '9786020000001',
            'stok' => 5,
        ]);

        Buku::create([
            'kategori_id' => $database->id,
            'judul' => 'Mastering PostgreSQL',
            'isbn' => '9786020000002',
            'stok' => 4,
        ]);

        Buku::create([
            'kategori_id' => $jaringan->id,
            'judul' => 'Dasar-Dasar Jaringan Komputer',
            'isbn' => '9786020000003',
            'stok' => 6,
        ]);

        Buku::create([
            'kategori_id' => $teknologi->id,
            'judul' => 'Teknologi Informasi Modern',
            'isbn' => '9786020000004',
            'stok' => 3,
        ]);

        Buku::create([
            'kategori_id' => $web->id,
            'judul' => 'Modern Web Development',
            'isbn' => '9786020000005',
            'stok' => 5,
        ]);
    }
}