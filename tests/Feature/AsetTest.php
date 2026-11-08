<?php

namespace Tests\Feature;

use App\Livewire\Aset\Form;
use App\Livewire\Aset\Index;
use App\Livewire\Aset\Show;
use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Perawatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AsetTest extends TestCase
{
    use RefreshDatabase;

    public function test_dasbor_menampilkan_10_aset_per_halaman(): void
    {
        Aset::factory(25)->create();

        // pastikan layout Bootstrap yang dipakai
        $this->get('/aset')->assertOk()->assertSee('Dasbor Aset')->assertSee('bootstrap@5.3.3', false);

        Livewire::test(Index::class)->assertViewHas('daftarAset', fn ($p) => $p->count() === 10 && $p->total() === 25);
    }

    public function test_filter_dan_ringkasan_dasbor(): void
    {
        Aset::factory()->create(['nama' => 'Ventilator ICU', 'status' => 'baik']);
        Aset::factory()->create(['nama' => 'Monitor Pasien', 'status' => 'rusak']);

        Livewire::test(Index::class)
            ->assertViewHas('ringkasan', fn ($r) => $r['total'] === 2 && $r['baik'] === 1 && $r['rusak'] === 1)
            ->set('cari', 'Ventilator')
            ->assertViewHas('daftarAset', fn ($p) => $p->total() === 1)
            ->set('cari', '')
            ->set('status', 'rusak')
            ->assertViewHas('daftarAset', fn ($p) => $p->total() === 1 && $p->first()->nama === 'Monitor Pasien');
    }

    public function test_tambah_aset_gagal_bila_field_wajib_kosong(): void
    {
        Livewire::test(Form::class)
            ->set('status', 'baik')
            ->call('simpan')
            ->assertHasErrors(['nama', 'kategori_id', 'lokasi_id'])
            ->assertSee('Nama aset wajib diisi.');
    }

    public function test_tambah_aset_menghasilkan_kode_otomatis(): void
    {
        $k = Kategori::factory()->create();
        $l = Lokasi::factory()->create();

        Livewire::test(Form::class)
            ->set('nama', 'Syringe Pump')
            ->set('kategori_id', (string) $k->id)
            ->set('lokasi_id', (string) $l->id)
            ->call('simpan')
            ->assertHasNoErrors()
            ->assertRedirect(route('aset.index'));

        $this->assertDatabaseHas('aset', ['nama' => 'Syringe Pump', 'kode_aset' => 'AST-0001', 'merk' => null]);
    }

    public function test_ubah_aset_dengan_nomor_seri_sendiri_tidak_dianggap_duplikat(): void
    {
        $aset = Aset::factory()->create(['nomor_seri' => 'SN-1']);

        Livewire::test(Form::class, ['aset' => $aset])
            ->assertSet('nomor_seri', 'SN-1')
            ->set('nama', 'Nama Baru')
            ->call('simpan')
            ->assertHasNoErrors();

        $this->assertSame('Nama Baru', $aset->fresh()->nama);
    }

    public function test_nomor_seri_duplikat_ditolak(): void
    {
        Aset::factory()->create(['nomor_seri' => 'SN-1']);
        $k = Kategori::factory()->create();
        $l = Lokasi::factory()->create();

        Livewire::test(Form::class)
            ->set('nama', 'X')->set('kategori_id', (string) $k->id)->set('lokasi_id', (string) $l->id)
            ->set('nomor_seri', 'SN-1')
            ->call('simpan')
            ->assertHasErrors(['nomor_seri']);
    }

    public function test_hapus_aset_adalah_soft_delete_dan_riwayat_tetap(): void
    {
        $aset = Aset::factory()->create();
        Perawatan::factory()->create(['aset_id' => $aset->id]);

        Livewire::test(Index::class)
            ->call('konfirmasiHapus', $aset->id)
            ->assertSee('Hapus aset ini?')
            ->call('hapus');

        $this->assertSoftDeleted('aset', ['id' => $aset->id]);
        $this->assertDatabaseCount('perawatan', 1);
    }

    public function test_detail_menampilkan_kategori_lokasi_dan_riwayat(): void
    {
        $aset = Aset::factory()->create();
        Perawatan::factory()->create(['aset_id' => $aset->id, 'keterangan' => 'Ganti baterai cadangan']);

        $this->get(route('aset.show', $aset))
            ->assertOk()
            ->assertSee($aset->kategori->nama)
            ->assertSee($aset->lokasi->nama)
            ->assertSee('Ganti baterai cadangan');

        Livewire::test(Show::class, ['aset' => $aset])->assertOk();
    }

    public function test_pagination_dapat_pindah_halaman(): void
    {
        Aset::factory(25)->create();

        Livewire::test(Index::class)
            ->assertSee('Menampilkan 1–10 dari 25 aset')
            ->assertSee('Berikutnya ›')
            ->call('gotoPage', 3)
            ->assertSee('Menampilkan 21–25 dari 25 aset');
    }

    public function test_baris_kalibrasi_terlambat_diberi_penanda(): void
    {
        Aset::factory()->create(['tgl_kalibrasi_berikutnya' => now()->subDays(3)]);

        Livewire::test(Index::class)->assertSee('Terlambat');
    }

    public function test_hapus_dari_halaman_detail_dengan_modal(): void
    {
        $aset = Aset::factory()->create();

        Livewire::test(Show::class, ['aset' => $aset])
            ->set('konfirmasiHapus', true)
            ->assertSee('Hapus aset ini?')
            ->call('hapus')
            ->assertRedirect(route('aset.index'));

        $this->assertSoftDeleted('aset', ['id' => $aset->id]);
    }

    public function test_halaman_ubah_dan_tambah_dapat_dibuka(): void
    {
        $aset = Aset::factory()->create();

        $this->get(route('aset.create'))->assertOk()->assertSee('Dibuat otomatis');
        $this->get(route('aset.edit', $aset))->assertOk()->assertSee($aset->kode_aset);
    }

    public function test_status_kalibrasi(): void
    {
        $this->assertSame('tidak_wajib', (new Aset)->statusKalibrasi());
        $this->assertSame('terlambat', (new Aset(['tgl_kalibrasi_berikutnya' => now()->subDay()]))->statusKalibrasi());
        $this->assertSame('segera', (new Aset(['tgl_kalibrasi_berikutnya' => now()->addDays(30)]))->statusKalibrasi());
        $this->assertSame('aman', (new Aset(['tgl_kalibrasi_berikutnya' => now()->addDays(31)]))->statusKalibrasi());
    }
}
