<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
            PengembalianSeeder::class,
        ]);
    }
}
