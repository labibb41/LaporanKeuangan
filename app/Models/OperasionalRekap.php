<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperasionalRekap extends Model
{
    use HasFactory;

    protected $table = 'operasional_rekap';

    protected $fillable = [
        'bulan',
        'tahun',
        'kapal_id',
        'rute',
        'trips',
        'tonase',
        'sangu_supir',
        'terpal',
        'operasional',
        'telly_id',
        'tanggal_kegiatan',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
            'tonase' => 'decimal:2',
            'sangu_supir' => 'decimal:2',
            'terpal' => 'decimal:2',
            'operasional' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    public function kapal(): BelongsTo
    {
        return $this->belongsTo(Kapal::class);
    }

    public function telly(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'telly_id');
    }

    public function getTotalAttribute(): float
    {
        return (float) $this->sangu_supir
            + (float) $this->terpal
            + (float) $this->operasional;
    }
}
