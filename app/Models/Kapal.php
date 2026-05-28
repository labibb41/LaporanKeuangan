<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';

    protected $fillable = [
        'nama_kapal',
        'nama_paguyuban',
        'voyage',
        'pemilik_kapal',
        'tahun_pembuatan',
        'kapasitas_ton',
        'status',
        'keterangan',
        'tarif_tonase',
    ];

    protected function casts(): array
    {
        return [
            'tarif_tonase'    => 'decimal:2',
            'kapasitas_ton'   => 'decimal:2',
            'tahun_pembuatan' => 'integer',
        ];
    }

    public function transaksiOperasional(): HasMany
    {
        return $this->hasMany(TransaksiOperasional::class);
    }
}
