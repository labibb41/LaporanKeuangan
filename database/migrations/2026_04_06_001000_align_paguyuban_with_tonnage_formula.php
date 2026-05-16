<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            UPDATE paguyuban
            SET tanggal = COALESCE(
                    (SELECT tanggal FROM transaksi_operasional WHERE transaksi_operasional.id = paguyuban.transaksi_id),
                    tanggal
                ),
                jumlah_orang = NULL,
                tarif = 500,
                total_bayar = COALESCE(
                    (SELECT tonase FROM transaksi_operasional WHERE transaksi_operasional.id = paguyuban.transaksi_id),
                    0
                ) * 500
        ');
    }

    public function down(): void
    {
        // Legacy paguyuban values cannot be restored reliably.
    }
};
