<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kapal', function (Blueprint $table) {
            $table->string('voyage')->nullable()->after('nama_kapal');
            $table->string('pemilik_kapal')->nullable()->after('voyage');
            $table->unsignedSmallInteger('tahun_pembuatan')->nullable()->after('pemilik_kapal');
            $table->decimal('kapasitas_ton', 10, 2)->nullable()->after('tahun_pembuatan');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('kapasitas_ton');
            $table->text('keterangan')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('kapal', function (Blueprint $table) {
            $table->dropColumn(['voyage', 'pemilik_kapal', 'tahun_pembuatan', 'kapasitas_ton', 'status', 'keterangan']);
        });
    }
};
