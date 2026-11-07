<?php

namespace Database\Factories;

use App\Models\Aset;
use App\Models\Perawatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerawatanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'aset_id' => Aset::factory(),
            'tanggal' => fake()->dateTimeBetween('-2 years', 'now'),
            'jenis' => fake()->randomElement(Perawatan::JENIS),
            'keterangan' => fake()->sentence(8),
            'teknisi' => fake()->optional(0.9)->name(),
            'biaya' => fake()->optional(0.8)->numberBetween(1, 50) * 100_000,
        ];
    }
}
