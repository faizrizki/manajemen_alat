<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perawatan extends Model
{
    use HasFactory;

    public const JENIS = ['servis', 'perbaikan', 'kalibrasi'];

    protected $table = 'perawatan';

    protected $fillable = ['aset_id', 'tanggal', 'jenis', 'keterangan', 'teknisi', 'biaya'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'biaya' => 'decimal:2',
        ];
    }

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }
}
