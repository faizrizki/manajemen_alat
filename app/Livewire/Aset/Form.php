<?php

namespace App\Livewire\Aset;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class Form extends Component
{
    public ?Aset $aset = null;

    #[Url(as: 'page')]
    public $halaman = 1;

    public $nama = '', $kategori_id = '', $lokasi_id = '', $merk = '', $nomor_seri = '';

    public $tanggal_pembelian = '', $harga = '', $status = 'baik', $tgl_kalibrasi_berikutnya = '', $catatan = '';

    public function mount(?Aset $aset = null)
    {
        if ($aset?->exists) {
            $this->aset = $aset;

            $this->fill($aset->only(['nama', 'kategori_id', 'lokasi_id', 'merk', 'nomor_seri', 'harga', 'status', 'catatan']));

            $this->tanggal_pembelian = $aset->tanggal_pembelian?->format('Y-m-d');
            $this->tgl_kalibrasi_berikutnya = $aset->tgl_kalibrasi_berikutnya?->format('Y-m-d');
        }
    }

    protected function rules()
    {
        return [
            'nama' => 'required|max:150',

            'kategori_id' => 'required|exists:kategori,id',
            'lokasi_id' => 'required|exists:lokasi,id',

            'merk' => 'nullable|max:100',

            'nomor_seri' => ['nullable', 'max:100', Rule::unique('aset', 'nomor_seri')->ignore($this->aset?->id)],

            'tanggal_pembelian' => 'nullable|date|before_or_equal:today',

            'harga' => 'nullable|numeric|min:0',

            'status' => ['required', Rule::in(array_keys(Aset::STATUS))],
            'tgl_kalibrasi_berikutnya' => 'nullable|date',
            'catatan' => 'nullable|max:1000',
        ];
    }

    protected function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'unique' => ':attribute sudah dipakai aset lain.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'date' => ':attribute bukan tanggal yang valid.',
            'before_or_equal' => ':attribute tidak boleh melebihi hari ini.',
            'in' => ':attribute yang dipilih tidak valid.',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'nama' => 'Nama aset',
            'kategori_id' => 'Kategori',
            'lokasi_id' => 'Lokasi',
            'merk' => 'Merk / tipe',
            'nomor_seri' => 'Nomor seri',
            'tanggal_pembelian' => 'Tanggal pembelian',
            'harga' => 'Harga',
            'status' => 'Status',
            'tgl_kalibrasi_berikutnya' => 'Kalibrasi berikutnya',
            'catatan' => 'Catatan',
        ];
    }

    public function simpan()
    {
        $data = $this->validate();

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);

        if ($this->aset) {
            $this->aset->update($data);
            session()->flash('sukses', 'Aset berhasil diperbarui.');

            return $this->redirectRoute('aset.index', ['page' => $this->halaman], navigate: true);
        }

        Aset::create($data + ['kode_aset' => Aset::kodeBaru()]);
        session()->flash('sukses', 'Aset berhasil ditambahkan.');

        return $this->redirectRoute('aset.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.aset.form', [
            'kode' => $this->aset?->kode_aset ?? 'Dibuat otomatis ('.Aset::kodeBaru().')',

            'daftarKategori' => Kategori::orderBy('nama')->get(),
            'daftarLokasi' => Lokasi::orderBy('nama')->get(),
        ]);
    }
}
