<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Perawatan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $kategori = collect([
            'Monitor Pasien', 'Syringe Pump', 'Infusion Pump', 'Kursi Roda', 'Ventilator', 'EKG',
        ])->map(fn ($nama) => Kategori::create(['nama' => $nama]));

        $lokasi = collect([
            ['ICU', 'Gedung A', '2'], ['IGD', 'Gedung A', '1'], ['Depo Alkes', 'Gedung B', '1'],
            ['Rawat Inap 1', 'Gedung B', '2'], ['Rawat Inap 2', 'Gedung B', '3'],
            ['Kamar Operasi', 'Gedung C', '1'], ['Radiologi', 'Gedung C', '2'], ['Poliklinik', 'Gedung A', '1'],
        ])->map(fn ($l) => Lokasi::create(['nama' => $l[0], 'gedung' => $l[1], 'lantai' => $l[2]]));

        $aset = collect(range(1, 50))->map(function ($i) use ($kategori, $lokasi) {
            $kalibrasi = match (true) {
                $i <= 5 => now()->subDays(rand(1, 45)),
                $i <= 15 => now()->addDays(rand(1, 30)),
                $i <= 35 => now()->addDays(rand(31, 365)),
                default => null,
            };

            return Aset::factory()->create([
                'kode_aset' => 'AST-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),

                'kategori_id' => $kategori->random()->id,
                'lokasi_id' => $lokasi->random()->id,
                'tgl_kalibrasi_berikutnya' => $kalibrasi,
            ]);
        });

        Perawatan::factory(80)->recycle($aset)->create();
    }
}
