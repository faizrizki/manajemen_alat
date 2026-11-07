<?php

namespace App\Livewire\Aset;

use App\Models\Aset;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $hapusId = null;

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
            'daftarAset' => Aset::with(['kategori', 'lokasi'])->latest('id')->paginate(10),

            'asetDihapus' => $this->hapusId ? Aset::find($this->hapusId) : null,
        ]);
    }
}
