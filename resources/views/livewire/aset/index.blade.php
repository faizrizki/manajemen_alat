@php
    $kalibrasi = [
        'terlambat' => ['Terlambat', 'kal-terlambat', '#FFF7F7'],
        'segera' => ['≤ 30 hari', 'kal-segera', '#FFFCF2'],
    ];
@endphp
<div class="d-flex flex-column gap-3">
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <h1 class="page-title">Dasbor Aset</h1>
            <p class="text-secondary mb-0" style="font-size: 14px">Daftar seluruh aset dan alat medis rumah sakit.</p>
        </div>
        <a href="{{ route('aset.create') }}" wire:navigate class="btn btn-navy d-inline-flex align-items-center gap-2" style="height: 38px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Aset
        </a>
    </div>

    @if (session('sukses'))
        <div class="alert alert-sukses alert-dismissible mb-0 py-3" role="status">
            {{ session('sukses') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    
    <div class="row g-3">
        @foreach ([
            ['Total Aset', $ringkasan['total'], '#2F80ED', '<path d="M21 8l-9-5-9 5 9 5 9-5zM3 8v8l9 5 9-5V8M12 13v8"/>'],
            ['Kondisi Baik', $ringkasan['baik'], '#4CAF6A', '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l3 3 5-6"/>'],
            ['Perlu Perhatian', $ringkasan['rusak'], '#EF5350', '<path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18h.01"/>'],
            ['Perlu Kalibrasi', $ringkasan['kalibrasi'], '#E8890C', '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'],
        ] as [$label, $angka, $warna, $ikon])
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 text-white position-relative overflow-hidden" style="background: {{ $warna }}">
                    <svg class="position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); opacity: .25" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ikon !!}</svg>
                    <div class="card-body text-end py-3">
                        <div class="fw-bold" style="font-size: 30px; line-height: 1.1">{{ $angka }}</div>
                        <div style="font-size: 13px; opacity: .9">{{ $label }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <input type="search" wire:model.live.debounce.400ms="cari" class="form-control" placeholder="Cari nama, kode, atau merk...">
                </div>
                <div class="col-6 col-md-3">
                    <select wire:model.live="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\Aset::STATUS as $nilai => $label)
                            <option value="{{ $nilai }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select wire:model.live="kategori" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($daftarKategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 940px">
                <thead>
                    <tr>
                        <th class="ps-3">No</th><th>Kode</th><th>Nama Aset</th><th>Kategori</th>
                        <th>Lokasi</th><th>Status</th><th>Kalibrasi Berikutnya</th><th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarAset as $aset)
                        @php($k = $kalibrasi[$aset->statusKalibrasi()] ?? null)
                        <tr wire:key="aset-{{ $aset->id }}" @if ($k) style="--bs-table-bg: {{ $k[2] }}" @endif>
                            <td class="ps-3 text-secondary">{{ $daftarAset->firstItem() + $loop->index }}</td>
                            <td class="kode">{{ $aset->kode_aset }}</td>
                            <td>
                                <div class="fw-semibold">{{ $aset->nama }}</div>
                                <div class="text-secondary" style="font-size: 12px">{{ $aset->merk }}</div>
                            </td>
                            <td>{{ $aset->kategori->nama }}</td>
                            <td>{{ $aset->lokasi->nama }}</td>
                            <td>
                                <span class="pil badge-{{ $aset->status }}">{{ \App\Models\Aset::STATUS[$aset->status] ?? $aset->status }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span>{{ $aset->tgl_kalibrasi_berikutnya?->format('d/m/Y') ?? '–' }}</span>
                                    @if ($k)
                                        <span class="pil {{ $k[1] }}">{{ $k[0] }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('aset.show', $aset) }}" wire:navigate class="btn-icon" title="Detail" aria-label="Detail {{ $aset->kode_aset }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('aset.edit', ['aset' => $aset, 'page' => $daftarAset->currentPage()]) }}" wire:navigate class="btn-icon" title="Ubah" aria-label="Ubah {{ $aset->kode_aset }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20h4L19 9l-4-4L4 16v4z"/></svg>
                                    </a>
                                    <button type="button" wire:click="konfirmasiHapus({{ $aset->id }})" class="btn-icon hapus" title="Hapus" aria-label="Hapus {{ $aset->kode_aset }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-5">Tidak ada aset yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-3">
            <span class="text-secondary" style="font-size: 13px">
                Menampilkan {{ $daftarAset->firstItem() ?? 0 }}–{{ $daftarAset->lastItem() ?? 0 }} dari {{ $daftarAset->total() }} aset
            </span>
            {{ $daftarAset->links('pagination.simpak') }}
        </div>
    </div>

    @if ($asetDihapus)
        <div class="modal d-block" tabindex="-1" role="alertdialog" aria-modal="true" aria-label="Konfirmasi hapus">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 440px">
                <div class="modal-content border-0" style="border-radius: 12px">
                    <div class="modal-body p-4">
                        <h2 class="h5 fw-bold">Hapus aset ini?</h2>
                        <p class="mb-3" style="font-size: 14px; line-height: 1.5"><strong>{{ $asetDihapus->kode_aset }} · {{ $asetDihapus->nama }}</strong> akan dihapus dari daftar. Riwayat perawatannya tetap tersimpan (soft delete).</p>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" wire:click="batalHapus" class="btn btn-outline-navy">Batal</button>
                            <button type="button" wire:click="hapus" class="btn btn-danger fw-semibold" style="background: #C62828; border-color: #C62828">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop show"></div>
    @endif
</div>
