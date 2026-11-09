@php
    $tgl = fn ($d) => $d?->format('d/m/Y') ?? '–';
    $lokasi = $aset->lokasi->nama.($aset->lokasi->gedung ? ' · '.$aset->lokasi->gedung.($aset->lokasi->lantai ? ' Lt. '.$aset->lokasi->lantai : '') : '');
    $info = [
        'Kode Aset' => $aset->kode_aset,
        'Nama' => $aset->nama,
        'Merk / Tipe' => $aset->merk ?? '–',
        'Nomor Seri' => $aset->nomor_seri ?? '–',
        'Kategori' => $aset->kategori->nama,
        'Lokasi' => $lokasi,
        'Tanggal Pembelian' => $tgl($aset->tanggal_pembelian),
        'Harga' => $aset->harga !== null ? 'Rp '.number_format((float) $aset->harga, 0, ',', '.') : '–',
        'Kalibrasi Berikutnya' => $tgl($aset->tgl_kalibrasi_berikutnya),
        'Catatan' => $aset->catatan ?? '–',
    ];
    $kalStatus = $aset->statusKalibrasi();
    $kalPil = ['terlambat' => ['Terlambat', 'kal-terlambat'], 'segera' => ['≤ 30 hari', 'kal-segera']][$kalStatus] ?? null;
    $kalTeks = ['aman' => 'Aman', 'tidak_wajib' => 'Tidak wajib'][$kalStatus] ?? null;
@endphp
<div class="d-flex flex-column gap-3">
    <a href="{{ route('aset.index') }}" wire:navigate class="fw-semibold text-decoration-none" style="font-size: 14px">‹ Kembali ke Dasbor</a>

    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
        <div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <h1 class="page-title">{{ $aset->nama }}</h1>
                <span class="pil badge-{{ $aset->status }}">{{ \App\Models\Aset::STATUS[$aset->status] ?? $aset->status }}</span>
            </div>
            <p class="text-secondary mb-0" style="font-size: 14px">{{ $aset->kode_aset }} · {{ $aset->merk ?? '–' }} · {{ $aset->lokasi->nama }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('aset.edit', $aset) }}" wire:navigate class="btn btn-navy d-inline-flex align-items-center" style="height: 38px">Ubah</a>
            <button type="button" wire:click="$set('konfirmasiHapus', true)" class="btn btn-outline-merah" style="height: 38px">Hapus</button>
        </div>
    </div>

    @if (session('sukses'))
        <div class="alert alert-sukses alert-dismissible mb-0 py-3" role="status">
            {{ session('sukses') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap gap-3 align-items-start">
        <div class="d-flex flex-column gap-3" style="flex: 999 1 520px; min-width: 0">
            <div class="card">
                <div class="card-header bg-white py-3 px-4"><h2 class="card-judul mb-0">Data Aset</h2></div>
                <dl class="mb-0 p-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 16px 22px">
                    @foreach ($info as $label => $nilai)
                        <div>
                            <dt class="dt-label">{{ $label }}</dt>
                            <dd class="mb-0 mt-1 fw-medium" style="font-size: 14px">{{ $nilai }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="card">
                <div class="card-header bg-white py-3 px-4"><h2 class="card-judul mb-0">Catat Perawatan</h2></div>
                <form wire:submit="catat" class="p-3 p-sm-4" novalidate>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label fw-semibold" for="tanggal">Tanggal</label>
                            <input id="tanggal" type="date" wire:model="tanggal" class="form-control @error('tanggal') is-invalid @enderror">
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label fw-semibold" for="jenis">Jenis</label>
                            <select id="jenis" wire:model="jenis" class="form-select @error('jenis') is-invalid @enderror">
                                @foreach (\App\Models\Perawatan::JENIS as $j)
                                    <option value="{{ $j }}">{{ ucfirst($j) }}</option>
                                @endforeach
                            </select>
                            @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label fw-semibold" for="teknisi">Teknisi</label>
                            <input id="teknisi" type="text" wire:model="teknisi" class="form-control @error('teknisi') is-invalid @enderror">
                            @error('teknisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label fw-semibold" for="biaya">Biaya (Rp)</label>
                            <input id="biaya" type="number" min="0" wire:model="biaya" class="form-control @error('biaya') is-invalid @enderror">
                            @error('biaya') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <textarea id="keterangan" rows="2" wire:model="keterangan" class="form-control @error('keterangan') is-invalid @enderror"></textarea>
                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-navy" wire:loading.attr="disabled">Simpan Perawatan</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3 px-4">
                    <h2 class="card-judul mb-0">Riwayat Perawatan</h2>
                    <span class="text-secondary" style="font-size: 13px">{{ $riwayat->count() }} catatan</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="min-width: 560px">
                        <thead>
                            <tr><th class="ps-4">Tanggal</th><th>Jenis</th><th>Keterangan</th><th>Teknisi</th><th class="pe-4">Biaya</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayat as $r)
                                <tr wire:key="perawatan-{{ $r->id }}">
                                    <td class="ps-4 text-nowrap">{{ $r->tanggal->format('d/m/Y') }}</td>
                                    <td><span class="pil jenis-{{ $r->jenis }}">{{ ucfirst($r->jenis) }}</span></td>
                                    <td style="color: #3A4553">{{ $r->keterangan }}</td>
                                    <td style="color: #3A4553">{{ $r->teknisi ?? '–' }}</td>
                                    <td class="pe-4 text-nowrap" style="color: #3A4553">{{ $r->biaya !== null ? 'Rp '.number_format((float) $r->biaya, 0, ',', '.') : '–' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada riwayat perawatan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <aside class="aside-kalibrasi" style="flex: 1 1 260px; min-width: 0">
            <div class="card">
                <div class="card-body p-3 d-flex flex-column gap-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="card-judul mb-0" style="font-size: 15px">Kalibrasi</h2>
                        @if ($kalPil)
                            <span class="pil {{ $kalPil[1] }}">{{ $kalPil[0] }}</span>
                        @elseif ($kalTeks)
                            <span class="text-secondary" style="font-size: 13px">{{ $kalTeks }}</span>
                        @endif
                    </div>
                    <span style="font-size: 13.5px; color: #3A4553">Berikutnya: <strong>{{ $tgl($aset->tgl_kalibrasi_berikutnya) }}</strong></span>
                    <span class="text-secondary" style="font-size: 12.5px">Mencatat riwayat berjenis Kalibrasi memperbarui tanggal ini otomatis.</span>
                </div>
            </div>
        </aside>
    </div>

    @if ($konfirmasiHapus)
        <div class="modal d-block" tabindex="-1" role="alertdialog" aria-modal="true" aria-label="Konfirmasi hapus">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 440px">
                <div class="modal-content border-0" style="border-radius: 12px">
                    <div class="modal-body p-4">
                        <h2 class="h5 fw-bold">Hapus aset ini?</h2>
                        <p class="mb-3" style="font-size: 14px; line-height: 1.5"><strong>{{ $aset->kode_aset }} · {{ $aset->nama }}</strong> akan dihapus dari daftar. Riwayat perawatannya tetap tersimpan (soft delete).</p>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" wire:click="$set('konfirmasiHapus', false)" class="btn btn-outline-navy">Batal</button>
                            <button type="button" wire:click="hapus" class="btn btn-danger fw-semibold" style="background: #C62828; border-color: #C62828">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop show"></div>
    @endif
</div>
