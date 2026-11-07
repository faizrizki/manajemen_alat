<?php

namespace App\Livewire\Aset;

use App\Models\Aset;
use Livewire\Component;

class Show extends Component
{
    public Aset $aset;

    public $konfirmasiHapus = false;

    public function hapus()
    {
        $this->aset->delete();

        session()->flash('sukses', 'Aset '.$this->aset->kode_aset.' berhasil dihapus.');

        return $this->redirectRoute('aset.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.aset.show', ['riwayat' => $this->aset->perawatan]);
    }
}
