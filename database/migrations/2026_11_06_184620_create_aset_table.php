<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->id();

            $table->string('kode_aset', 20)->unique();
            $table->string('nama', 150);

            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->foreignId('lokasi_id')->constrained('lokasi')->restrictOnDelete();

            $table->string('merk', 100)->nullable();

            $table->string('nomor_seri', 100)->nullable()->unique();
            $table->date('tanggal_pembelian')->nullable();

            $table->decimal('harga', 15, 2)->nullable();

            $table->string('status', 20)->default('baik')->index();

            $table->date('tgl_kalibrasi_berikutnya')->nullable()->index();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
