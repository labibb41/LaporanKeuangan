<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kapal', function (Blueprint $table) {
            $table->string('nama_paguyuban')->nullable()->after('nama_kapal');
        });
    }

    public function down(): void
    {
        Schema::table('kapal', function (Blueprint $table) {
            $table->dropColumn('nama_paguyuban');
        });
    }
};
