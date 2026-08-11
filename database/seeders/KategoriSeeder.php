<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::create([
            'nama_kategori' => 'Pemrograman',
        ]);

        Kategori::create([
            'nama_kategori' => 'Database',
        ]);

        Kategori::create([
            'nama_kategori' => 'Jaringan',
        ]);

        Kategori::create([
            'nama_kategori' => 'Teknologi',
        ]);

        Kategori::create([
            'nama_kategori' => 'Web Development',
        ]);
    }
}