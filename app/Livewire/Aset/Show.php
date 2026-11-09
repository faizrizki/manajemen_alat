<?php

namespace App\Livewire\Aset;

use App\Models\Aset;
use App\Models\Perawatan;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Show extends Component
{
    public Aset $aset;

    public $konfirmasiHapus = false;

    public $tanggal = '', $keterangan = '', $teknisi = '', $biaya = '';

    public $jenis = 'servis';

    public function mount(Aset $aset)
    {
        $this->aset = $aset;
        $this->tanggal = now()->format('Y-m-d');
    }

    public function catat()
    {
        $data = $this->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'jenis' => ['required', Rule::in(Perawatan::JENIS)],
            'keterangan' => 'required|max:1000',
            'teknisi' => 'nullable|max:100',
            'biaya' => 'nullable|numeric|min:0',
        ], [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'date' => ':attribute bukan tanggal yang valid.',
            'before_or_equal' => ':attribute tidak boleh melebihi hari ini.',
            'in' => ':attribute yang dipilih tidak valid.',
        ], [
            'tanggal' => 'Tanggal',
            'jenis' => 'Jenis',
            'keterangan' => 'Keterangan',
            'teknisi' => 'Teknisi',
            'biaya' => 'Biaya',
        ]);

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);

        $this->aset->perawatan()->create($data);

        if ($data['jenis'] === 'kalibrasi' && $this->aset->tgl_kalibrasi_berikutnya) {
            $this->aset->update([
                'tgl_kalibrasi_berikutnya' => Carbon::parse($data['tanggal'])->addYear(),
            ]);
        }

        $this->reset(['keterangan', 'teknisi', 'biaya']);

        session()->flash('sukses', 'Riwayat perawatan berhasil dicatat.');
    }

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
