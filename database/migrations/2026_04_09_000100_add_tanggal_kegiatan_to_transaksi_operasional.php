<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_operasional', function (Blueprint $table) {
            $table->date('tanggal_kegiatan')->nullable()->after('tanggal');
        });

        DB::table('transaksi_operasional')
            ->whereNull('tanggal_kegiatan')
            ->update([
                'tanggal_kegiatan' => DB::raw('tanggal'),
            ]);
    }

    public function down(): void
    {
        Schema::table('transaksi_operasional', function (Blueprint $table) {
            $table->dropColumn('tanggal_kegiatan');
        });
    }
};
