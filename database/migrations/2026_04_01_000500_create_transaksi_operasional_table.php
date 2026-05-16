<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_operasional', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kapal_id')->constrained('kapal')->cascadeOnDelete();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->string('rute');
            $table->unsignedInteger('ritase')->default(1);
            $table->decimal('tonase', 12, 2)->default(0);
            $table->decimal('sangu_supir', 15, 2)->default(0);
            $table->decimal('terpal', 15, 2)->default(0);
            $table->decimal('operasional', 15, 2)->default(0);
            $table->foreignId('telly_id')->nullable()->constrained('karyawan')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_operasional');
    }
};
