# SIMPAK-RS Lite

Aplikasi manajemen aset dan alat medis rumah sakit, dibuat dengan Laravel dan Livewire.

## Fitur

- Dasbor daftar aset dengan pagination (10 data per halaman)
- Kartu ringkasan: total aset, kondisi baik, perlu perhatian (rusak, dalam perbaikan, perlu perbaikan), dan perlu kalibrasi
- Pencarian (nama, kode, atau merk) dan filter status serta kategori, langsung tanpa reload halaman
- Tambah dan ubah aset dalam satu form, dengan validasi
- Detail aset: data lengkap, status kalibrasi, dan riwayat perawatan
- Hapus aset dengan modal konfirmasi (soft delete, data tetap tersimpan)
- Peringatan kalibrasi: baris diberi warna dan badge bila terlambat atau jatuh tempo dalam 30 hari
- Kode aset dibuat otomatis (AST-0001, AST-0002, dan seterusnya)

## Teknologi

- Laravel 13, PHP 8.3
- Livewire 4 (interaksi tanpa reload halaman)
- MySQL
- Bootstrap 5.3 (CDN)

## Struktur Proyek

```
app/
├── Livewire/Aset/
│   ├── Index.php      # dasbor, kartu ringkasan, pencarian, filter, pagination, hapus
│   ├── Form.php       # tambah DAN ubah aset
│   └── Show.php       # detail dan riwayat perawatan
└── Models/
    ├── Aset.php       # relasi, status, kode, kalibrasi
    ├── Kategori.php
    ├── Lokasi.php
    └── Perawatan.php
database/
├── migrations/        # tabel kategori, lokasi, aset, perawatan
├── factories/         # data contoh
└── seeders/DatabaseSeeder.php   # 50 aset dan 80 riwayat perawatan contoh
resources/views/
├── components/layouts/app.blade.php   # layout + Bootstrap CDN
├── pagination/simpak.blade.php        # tampilan tombol halaman
└── livewire/aset/
    ├── index.blade.php
    ├── form.blade.php
    └── show.blade.php
routes/web.php
```

## Cara Menjalankan

Kebutuhan: PHP 8.3 ke atas, Composer, dan MySQL.

1. Buat database kosong bernama `manajemen_alat` di MySQL.

2. Pasang dependensi dan siapkan file `.env` (bila folder `vendor` dan `.env` sudah ada, langkah ini boleh dilewati):

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

3. Pastikan bagian database di `.env` seperti berikut (sesuaikan username dan password MySQL):

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=manajemen_alat
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Buat tabel dan isi data contoh:

   ```bash
   php artisan migrate --seed
   ```

5. Jalankan aplikasi, lalu buka http://localhost:8000

   ```bash
   php artisan serve
   ```

## Menjalankan Tes

```bash
php artisan test
```

Tes memakai SQLite in-memory, jadi tidak menyentuh database MySQL.
