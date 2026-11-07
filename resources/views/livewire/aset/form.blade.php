<div class="d-flex flex-column gap-3" style="max-width: 860px">
    <a href="{{ route('aset.index', ['page' => $halaman]) }}" wire:navigate class="fw-semibold text-decoration-none">‹ Kembali ke Dasbor</a>

    <div>
        <h1 class="page-title">{{ $aset ? 'Ubah Aset · '.$aset->kode_aset : 'Tambah Aset' }}</h1>
        <p class="text-secondary mb-0" style="font-size: 14px">Isian bertanda <span class="text-danger">*</span> wajib diisi. Satu form ini dipakai untuk tambah dan ubah aset.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error mb-0 py-3" role="alert">Ada {{ $errors->count() }} isian yang perlu diperbaiki.</div>
    @endif

    <form wire:submit="simpan" class="card" novalidate>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold" for="kode">Kode Aset</label>
                    <input id="kode" type="text" class="form-control kode" value="{{ $kode }}" disabled>
                    <div class="form-text">Dibuat otomatis oleh sistem dan tidak dapat diubah.</div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold" for="nama">Nama Aset <span class="text-danger">*</span></label>
                    <input id="nama" type="text" wire:model="nama" placeholder="Contoh: Syringe Pump" class="form-control @error('nama') is-invalid @enderror">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="kategori_id">Kategori <span class="text-danger">*</span></label>
                    <select id="kategori_id" wire:model="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                        <option value="">Pilih kategori…</option>
                        @foreach ($daftarKategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="lokasi_id">Lokasi <span class="text-danger">*</span></label>
                    <select id="lokasi_id" wire:model="lokasi_id" class="form-select @error('lokasi_id') is-invalid @enderror">
                        <option value="">Pilih lokasi…</option>
                        @foreach ($daftarLokasi as $l)
                            <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    @error('lokasi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="merk">Merk / Tipe</label>
                    <input id="merk" type="text" wire:model="merk" placeholder="Contoh: Nexa Medical SP-200" class="form-control @error('merk') is-invalid @enderror">
                    @error('merk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="nomor_seri">Nomor Seri</label>
                    <input id="nomor_seri" type="text" wire:model="nomor_seri" placeholder="Contoh: SN-48213" class="form-control @error('nomor_seri') is-invalid @enderror">
                    @error('nomor_seri') <div class="invalid-feedback">{{ $message }}</div> @else <div class="form-text">Boleh kosong; bila diisi harus unik.</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="tanggal_pembelian">Tanggal Pembelian</label>
                    <input id="tanggal_pembelian" type="date" wire:model="tanggal_pembelian" class="form-control @error('tanggal_pembelian') is-invalid @enderror">
                    @error('tanggal_pembelian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="harga">Harga (Rp)</label>
                    <input id="harga" type="number" min="0" wire:model="harga" placeholder="0" class="form-control @error('harga') is-invalid @enderror">
                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
                    <select id="status" wire:model="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach (\App\Models\Aset::STATUS as $nilai => $label)
                            <option value="{{ $nilai }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="tgl_kalibrasi_berikutnya">Kalibrasi Berikutnya</label>
                    <input id="tgl_kalibrasi_berikutnya" type="date" wire:model="tgl_kalibrasi_berikutnya" class="form-control @error('tgl_kalibrasi_berikutnya') is-invalid @enderror">
                    @error('tgl_kalibrasi_berikutnya') <div class="invalid-feedback">{{ $message }}</div> @else <div class="form-text">Kosongkan bila alat tidak wajib kalibrasi.</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold" for="catatan">Catatan</label>
                    <textarea id="catatan" rows="3" wire:model="catatan" placeholder="Kelengkapan, kondisi saat diterima, dll." class="form-control @error('catatan') is-invalid @enderror"></textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2 py-3">
            <a href="{{ route('aset.index', ['page' => $halaman]) }}" wire:navigate class="btn btn-outline-navy">Kembali</a>
            <button type="submit" class="btn btn-navy" style="min-width: 110px" wire:loading.attr="disabled">Simpan</button>
        </div>
    </form>
</div>
