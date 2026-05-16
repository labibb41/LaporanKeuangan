<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paguyuban extends Model
{
    use HasFactory;

    public const DEFAULT_TARIF = 500;

    protected $table = 'paguyuban';

    protected $fillable = [
        'transaksi_id',
        'tanggal',
        'jumlah_orang',
        'tarif',
        'total_bayar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'tarif' => 'decimal:2',
            'total_bayar' => 'decimal:2',
        ];
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiOperasional::class, 'transaksi_id');
    }
}
