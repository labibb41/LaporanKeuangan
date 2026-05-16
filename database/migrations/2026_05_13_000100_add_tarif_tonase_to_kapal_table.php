<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kapal', function (Blueprint $table) {
            $table->decimal('tarif_tonase', 15, 2)->default(0)->after('nama_kapal');
        });
    }

    public function down(): void
    {
        Schema::table('kapal', function (Blueprint $table) {
            $table->dropColumn('tarif_tonase');
        });
    }
};
