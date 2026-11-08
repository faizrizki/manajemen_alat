<?php

namespace App\Livewire\Aset;

use App\Models\Aset;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kategori;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    public $hapusId = null;

    #[Url]
    public $cari = '';

    #[Url]
    public $status = '';

    #[Url]
    public $kategori = '';

    public function updatedCari() { $this->resetPage(); }
    public function updatedStatus() { $this->resetPage(); }
    public function updatedKategori() { $this->resetPage(); }

    public function konfirmasiHapus($id)
    {
        $this->hapusId = $id;
    }

    public function batalHapus()
    {
        $this->hapusId = null;
    }

    public function hapus()
    {
        Aset::findOrFail($this->hapusId)->delete();

        $this->hapusId = null;

        session()->flash('sukses', 'Aset berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.aset.index', [
            'daftarAset' => Aset::with(['kategori', 'lokasi'])
                ->when($this->cari, function ($q) {
                    $q->where(function ($q) {
                        $q->where('nama', 'like', '%'.$this->cari.'%')
                        ->orWhere('kode_aset', 'like', '%'.$this->cari.'%')
                        ->orWhere('merk', 'like', '%'.$this->cari.'%');
                    });
                })
                ->when($this->status, fn ($q) => $q->where('status', $this->status))
                ->when($this->kategori, fn ($q) => $q->where('kategori_id', $this->kategori))
                ->latest('id')
                ->paginate(10),

            'daftarKategori' => Kategori::orderBy('nama')->get(),

            'asetDihapus' => $this->hapusId ? Aset::find($this->hapusId) : null,

            'ringkasan' => [
                'total' => Aset::count(),
                'baik' => Aset::where('status', 'baik')->count(),
                'rusak' => Aset::whereIn('status', ['rusak', 'dalam_perbaikan', 'perlu_perbaikan'])->count(),

                'kalibrasi' => Aset::whereNotNull('tgl_kalibrasi_berikutnya')
                    ->whereDate('tgl_kalibrasi_berikutnya', '<=', now()->addDays(30))
                    ->count(),
            ]
        ]);
    }
}
