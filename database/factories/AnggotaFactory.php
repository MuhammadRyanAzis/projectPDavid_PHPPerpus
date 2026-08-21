<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    protected $model = Anggota::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nomor_anggota' => 'AGT'.fake()->unique()->numberBetween(1000, 9999),
            'alamat' => fake()->address(),
            'status' => 'aktif',
        ];
    }
}
