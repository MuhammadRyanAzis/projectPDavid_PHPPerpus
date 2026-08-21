<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengembalian>
 */
class PengembalianFactory extends Factory
{
    protected $model = Pengembalian::class;

    public function definition(): array
    {
        return [
            'peminjaman_id' => Peminjaman::factory(['status' => 'dikembalikan']),
            'petugas_id' => User::factory(),
            'tanggal_pengembalian' => now()->format('Y-m-d'),
            'denda' => 0.00,
        ];
    }
}
