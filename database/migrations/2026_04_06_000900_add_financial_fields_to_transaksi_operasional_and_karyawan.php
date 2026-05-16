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
            $table->decimal('pendapatan', 15, 2)->default(0)->after('tonase');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->decimal('tarif_telly', 15, 2)->default(0)->after('npwp');
            $table->decimal('pph_persen', 5, 2)->default(0)->after('tarif_telly');
        });

        DB::table('transaksi_operasional')
            ->update([
                'pendapatan' => DB::raw('operasional'),
                'operasional' => 0,
            ]);
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn(['tarif_telly', 'pph_persen']);
        });

        Schema::table('transaksi_operasional', function (Blueprint $table) {
            $table->dropColumn('pendapatan');
        });
    }
};
