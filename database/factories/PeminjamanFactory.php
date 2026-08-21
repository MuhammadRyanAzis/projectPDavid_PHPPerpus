<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        $tglPinjam = fake()->dateTimeBetween('-1 month', 'now');
        $tglJatuhTempo = (clone $tglPinjam)->modify('+7 days');

        return [
            'anggota_id' => Anggota::factory(),
            'buku_id' => Buku::factory(),
            'petugas_id' => User::factory(),
            'tanggal_pinjam' => $tglPinjam->format('Y-m-d'),
            'tanggal_jatuh_tempo' => $tglJatuhTempo->format('Y-m-d'),
            'status' => 'dipinjam',
        ];
    }
}
