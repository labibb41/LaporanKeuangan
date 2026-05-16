<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paguyuban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->unique()->constrained('transaksi_operasional')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedInteger('jumlah_orang')->nullable();
            $table->decimal('tarif', 15, 2)->default(500);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paguyuban');
    }
};
