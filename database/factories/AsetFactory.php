<?php

namespace Database\Factories;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_aset' => 'AST-'.fake()->unique()->numerify('####'),
            'nama' => fake()->randomElement(['Monitor Pasien', 'Syringe Pump', 'Kursi Roda', 'Infusion Pump', 'Ventilator', 'EKG']).' '.fake()->bothify('??-###'),

            'kategori_id' => Kategori::factory(),
            'lokasi_id' => Lokasi::factory(),
            'merk' => fake()->randomElement(['Philips', 'Mindray', 'GE Healthcare', 'Terumo', 'Nihon Kohden']),
            'nomor_seri' => fake()->unique()->bothify('SN-########'),
            'tanggal_pembelian' => fake()->dateTimeBetween('-8 years', '-1 month'),

            'harga' => fake()->numberBetween(5, 500) * 1_000_000,

            'status' => fake()->randomElement(['baik', 'baik', 'baik', 'perlu_perbaikan', 'dalam_perbaikan', 'rusak']),

            'tgl_kalibrasi_berikutnya' => fake()->optional(0.7)->dateTimeBetween('-60 days', '+365 days'),
            'catatan' => fake()->optional(0.3)->sentence(),
        ];
    }
}
