<?php

namespace Database\Factories;

use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class LokasiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(2, true),
            'gedung' => fake()->randomElement(['Gedung A', 'Gedung B', 'Gedung C']),
            'lantai' => (string) fake()->numberBetween(1, 5),
        ];
    }
}
