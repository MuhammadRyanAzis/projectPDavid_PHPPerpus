<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    protected $model = Buku::class;

    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::factory(),
            'judul' => fake()->sentence(3),
            'isbn' => fake()->unique()->isbn13(),
            'stok' => fake()->numberBetween(1, 20),
        ];
    }
}
