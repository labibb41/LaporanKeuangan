<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->string('no_hp')->nullable()->after('nama');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->date('tanggal_bergabung')->nullable()->after('alamat');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('tanggal_bergabung');
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'alamat', 'tanggal_bergabung', 'status']);
        });
    }
};
