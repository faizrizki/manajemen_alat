<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aset';

    protected $fillable = [
        'kode_aset', 'nama', 'kategori_id', 'lokasi_id', 'merk', 'nomor_seri',
        'tanggal_pembelian', 'harga', 'status', 'tgl_kalibrasi_berikutnya', 'catatan',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'tgl_kalibrasi_berikutnya' => 'date',
        'harga' => 'decimal:2',
    ];

    public const STATUS = [
        'baik' => 'Baik',
        'perlu_perbaikan' => 'Perlu Perbaikan',
        'dalam_perbaikan' => 'Dalam Perbaikan',
        'rusak' => 'Rusak',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function perawatan()
    {
        return $this->hasMany(Perawatan::class)->latest('tanggal');
    }

    public static function kodeBaru(): string
    {
        $terakhir = static::withTrashed()->max('id') ?? 0;

        return 'AST-'.str_pad($terakhir + 1, 4, '0', STR_PAD_LEFT);
    }

    public function statusKalibrasi(): string
    {
        if (! $this->tgl_kalibrasi_berikutnya) {
            return 'tidak_wajib';
        }

        $sisaHari = (int) now()->startOfDay()->diffInDays($this->tgl_kalibrasi_berikutnya, false);

        if ($sisaHari < 0) {
            return 'terlambat';
        }

        return $sisaHari <= 30 ? 'segera' : 'aman';
    }
}
