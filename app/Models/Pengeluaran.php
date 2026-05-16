<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'tanggal',
        'jenis',
        'nama_kegiatan',
        'jumlah',
        'penerima',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    #[Scope]
    protected function periode(Builder $query, ?int $bulan = null, ?int $tahun = null): void
    {
        $query
            ->when($bulan, fn (Builder $builder) => $builder->whereMonth('tanggal', $bulan))
            ->when($tahun, fn (Builder $builder) => $builder->whereYear('tanggal', $tahun));
    }
}
