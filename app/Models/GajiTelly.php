<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GajiTelly extends Model
{
    use HasFactory;

    protected $table = 'gaji_telly';

    protected $fillable = [
        'karyawan_id',
        'transaksi_id',
        'gaji',
        'gaji_total',
        'pph',
        'gaji_bersih',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'gaji' => 'decimal:2',
            'gaji_total' => 'decimal:2',
            'pph' => 'decimal:2',
            'gaji_bersih' => 'decimal:2',
        ];
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiOperasional::class, 'transaksi_id');
    }
}
