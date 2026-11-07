<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->date('tanggal');

            $table->string('jenis', 20);
            $table->text('keterangan');
            $table->string('teknisi', 100)->nullable();
            $table->decimal('biaya', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawatan');
    }
};
