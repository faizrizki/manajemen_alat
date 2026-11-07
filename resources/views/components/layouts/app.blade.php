@php
    $crumb = match (true) {
        request()->routeIs('aset.create', 'aset.edit') => 'Dasbor Aset / Form Aset',
        request()->routeIs('aset.show') => 'Dasbor Aset / '.(request()->route('aset')->kode_aset ?? ''),
        default => 'Dasbor Aset',
    };

    $logo = file_exists(public_path('img/logo.png')) ? asset('img/logo.png') : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Manajemen Aset' }} · Muhammad Rizki</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #233069;
            --navy-dark: #1A2552;
            --sky: #58C3F0;
            --sky-soft: #E6F6FD;
            --muted: #5B6675;

            --bs-body-font-family: Inter, system-ui, sans-serif;
            --bs-body-bg: #F5F7FA;
            --bs-body-color: #1B2430;
            --bs-border-color: #E3E8EF;
            --bs-link-color: var(--navy);
            --bs-link-hover-color: var(--navy-dark);
        }
        .text-secondary { color: var(--muted) !important; }
        a:focus-visible,
        button:focus-visible { outline: 2px solid var(--sky); outline-offset: 2px; }

        .app-header { height: 60px; background: #fff; border-bottom: 1px solid var(--bs-border-color); }
        .app-brand { display: flex; align-items: center; gap: 12px; color: var(--navy); font-weight: 700; font-size: 15px; text-decoration: none; }
        .app-brand img { height: 32px; width: 120px; object-fit: contain; object-position: left center; }
        .app-brand .divider { height: 24px; width: 1px; background: var(--bs-border-color); }

        .app-side { flex: 0 0 220px; width: 220px; min-height: calc(100vh - 60px); background: #fff; border-right: 1px solid var(--bs-border-color); }
        .app-side .nav-link { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 6px; color: #3A4553; font-size: 14px; font-weight: 500; }
        .app-side .nav-link.active { background: var(--sky-soft); color: var(--navy); font-weight: 600; }
        .app-side .catatan { font-size: 11.5px; }
        .app-main { min-width: 0; max-width: 1240px; }

        .btn { --bs-btn-font-size: 14px; --bs-btn-border-radius: 6px; }

        .btn-navy {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--navy);
            --bs-btn-border-color: var(--navy);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--navy-dark);
            --bs-btn-hover-border-color: var(--navy-dark);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--navy-dark);
            --bs-btn-active-border-color: var(--navy-dark);
            font-weight: 600;
        }
        .btn-outline-navy {
            --bs-btn-color: var(--navy);
            --bs-btn-bg: #fff;
            --bs-btn-border-color: #CBD3DF;
            --bs-btn-hover-color: var(--navy);
            --bs-btn-hover-bg: #F1F4F8;
            --bs-btn-hover-border-color: #CBD3DF;
            --bs-btn-active-color: var(--navy);
            --bs-btn-active-bg: #F1F4F8;
            font-weight: 600;
        }
        .btn-outline-merah {
            --bs-btn-color: #C62828;
            --bs-btn-bg: #fff;
            --bs-btn-border-color: #F3C7C7;
            --bs-btn-hover-color: #C62828;
            --bs-btn-hover-bg: #FDECEC;
            --bs-btn-hover-border-color: #F3C7C7;
            --bs-btn-active-color: #C62828;
            --bs-btn-active-bg: #FDECEC;
            font-weight: 600;
        }

        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--bs-border-color); border-radius: 6px; background: #fff; color: var(--navy); }
        .btn-icon:hover { background: #F1F4F8; color: var(--navy); }
        .btn-icon.hapus { border-color: #F3C7C7; color: #C62828; }
        .btn-icon.hapus:hover { background: #FDECEC; }

        .btn-page { height: 32px; min-width: 32px; padding: 0 10px; border: 1px solid var(--bs-border-color); border-radius: 6px; background: #fff; color: var(--navy); font-size: 13px; font-weight: 600; }
        .btn-page.active { background: var(--navy); border-color: var(--navy); color: #fff; }
        .btn-page:disabled { opacity: .5; }

        .form-control,
        .form-select { font-size: 14px; }
        .form-control:focus,
        .form-select:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(88, 195, 240, .35); }
        .form-label { margin-bottom: 6px; font-size: 13px; }

        .page-title { margin: 0; color: var(--navy); font-size: 24px; font-weight: 700; }
        .card { border-radius: 10px; }
        .card-judul { color: var(--navy); font-size: 16px; font-weight: 600; }
        .dt-label { color: var(--muted); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }

        .table { font-size: 13.5px; }
        .table thead th { background: #F7F9FC; color: var(--muted); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; }
        .table > :not(caption) > * > * { padding: 11px 12px; }
        .kode { color: var(--navy); font-family: ui-monospace, SFMono-Regular, monospace; font-weight: 600; }

        .pil { display: inline-flex; align-items: center; height: 24px; padding: 0 10px; border-radius: 12px; font-size: 12px; font-weight: 600; white-space: nowrap; }

        .badge-baik { background: #E6F4EC; color: #1E7F4F; }
        .badge-perlu_perbaikan { background: #FFF4DB; color: #8A5A00; }
        .badge-dalam_perbaikan { background: var(--sky-soft); color: #0B5E85; }
        .badge-rusak { background: #FDECEC; color: #C62828; }

        .kal-terlambat { height: 22px; background: #FDECEC; color: #C62828; border: 1px solid #C62828; }
        .kal-segera { height: 22px; background: #FFF4DB; color: #8A5A00; border: 1px solid #8A5A00; }

        .jenis-servis { background: #E9ECF5; color: var(--navy); }
        .jenis-perbaikan { background: #FFF4DB; color: #8A5A00; }
        .jenis-kalibrasi { background: #E6F4EC; color: #1E7F4F; }

        .alert-sukses { background: #E6F4EC; color: #1E7F4F; font-size: 14px; font-weight: 500; border: 0; border-radius: 8px; }
        .alert-error { background: #FDECEC; color: #C62828; font-size: 14px; font-weight: 500; border: 0; border-radius: 8px; }
    </style>
</head>
<body>
    <header class="app-header d-flex align-items-center justify-content-between px-3 px-lg-4">
        <a href="{{ route('aset.index') }}" wire:navigate class="app-brand">
            @if ($logo)
                <img src="{{ $logo }}" alt="RS UMMI"><span class="divider"></span>
            @endif
            <span>Manajemen Aset</span>
        </a>

        <span class="small text-secondary d-none d-sm-inline">{{ $crumb }}</span>

        <nav class="d-lg-none d-flex gap-3 small fw-semibold" aria-label="Menu">
            <a href="{{ route('aset.index') }}" wire:navigate>Dasbor</a>
            <a href="{{ route('aset.create') }}" wire:navigate>Tambah</a>
        </nav>
    </header>

    <div class="d-flex">
        <nav class="app-side d-none d-lg-flex flex-column p-3 gap-1" aria-label="Menu">
            <a href="{{ route('aset.index') }}" wire:navigate
               class="nav-link {{ request()->routeIs('aset.index', 'aset.show') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                Dasbor Aset
            </a>
            <a href="{{ route('aset.create') }}" wire:navigate
               class="nav-link {{ request()->routeIs('aset.create', 'aset.edit') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Aset
            </a>
            <div class="catatan mt-auto small text-secondary p-2">Muhammad Rizki · Mini Project</div>
        </nav>

        <main class="app-main flex-grow-1 p-3 p-lg-4">
            {{ $slot }}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
