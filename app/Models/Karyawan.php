<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'nama',
        'no_hp',
        'alamat',
        'tanggal_bergabung',
        'status',
        'jabatan',
        'ktp',
        'npwp',
        'tarif_telly',
        'pph_persen',
    ];

    protected function casts(): array
    {
        return [
            'tarif_telly'      => 'decimal:2',
            'pph_persen'       => 'decimal:2',
            'tanggal_bergabung' => 'date',
        ];
    }

    public function transaksiTelly(): HasMany
    {
        return $this->hasMany(TransaksiOperasional::class, 'telly_id');
    }

    public function gajiTelly(): HasMany
    {
        return $this->hasMany(GajiTelly::class);
    }
}
