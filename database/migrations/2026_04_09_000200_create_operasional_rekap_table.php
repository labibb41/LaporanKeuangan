<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operasional_rekap', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->foreignId('kapal_id')->constrained('kapal')->cascadeOnDelete();
            $table->string('rute')->nullable();
            $table->unsignedInteger('trips')->nullable();
            $table->decimal('tonase', 12, 2)->nullable();
            $table->decimal('sangu_supir', 15, 2)->nullable();
            $table->decimal('terpal', 15, 2)->nullable();
            $table->decimal('operasional', 15, 2)->default(0);
            $table->foreignId('telly_id')->nullable()->constrained('karyawan')->nullOnDelete();
            $table->date('tanggal_kegiatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['bulan', 'tahun', 'kapal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operasional_rekap');
    }
};
