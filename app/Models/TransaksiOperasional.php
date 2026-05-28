<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransaksiOperasional extends Model
{
    use HasFactory;

    protected $table = 'transaksi_operasional';

    protected $fillable = [
        'tanggal',
        'tanggal_kegiatan',
        'kapal_id',
        'kendaraan_id',
        'rute',
        'ritase',
        'tonase',
        'pendapatan',
        'sangu_supir',
        'terpal',
        'operasional',
        'telly_id',
        'keterangan',
        'created_by',
        'updated_by',
        'version',
    ];

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

        static::created(function ($model) {
            if (auth()->check()) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'model_type' => self::class,
                    'model_id' => $model->id,
                    'action' => 'created',
                    'description' => auth()->user()->name . ' menambahkan transaksi baru untuk kapal ' . ($model->kapal->nama_kapal ?? 'Unknown'),
                ]);
            }
        });

        static::updated(function ($model) {
            if (auth()->check()) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'model_type' => self::class,
                    'model_id' => $model->id,
                    'action' => 'updated',
                    'description' => auth()->user()->name . ' mengubah transaksi kapal ' . ($model->kapal->nama_kapal ?? 'Unknown'),
                ]);
            }
        });

        static::deleted(function ($model) {
            if (auth()->check()) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'model_type' => self::class,
                    'model_id' => $model->id,
                    'action' => 'deleted',
                    'description' => auth()->user()->name . ' menghapus transaksi kapal ' . ($model->kapal->nama_kapal ?? 'Unknown'),
                ]);
            }
        });
    }

    protected $appends = [
        'total_biaya',
        'laba_kotor',
        'total_lapangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'tanggal_kegiatan' => 'date',
            'tonase' => 'decimal:2',
            'pendapatan' => 'decimal:2',
            'sangu_supir' => 'decimal:2',
            'terpal' => 'decimal:2',
            'operasional' => 'decimal:2',
        ];
    }

    public function kapal(): BelongsTo
    {
        return $this->belongsTo(Kapal::class);
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function telly(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'telly_id');
    }

    public function gajiTelly(): HasOne
    {
        return $this->hasOne(GajiTelly::class, 'transaksi_id');
    }

    public function paguyuban(): HasOne
    {
        return $this->hasOne(Paguyuban::class, 'transaksi_id');
    }

    #[Scope]
    protected function bulan(Builder $query, int $bulan, int $tahun): void
    {
        $tanggal = $this->qualifyColumn('tanggal');

        $query
            ->whereMonth($tanggal, $bulan)
            ->whereYear($tanggal, $tahun);
    }

    #[Scope]
    protected function periode(Builder $query, ?int $bulan = null, ?int $tahun = null): void
    {
        $tanggal = $this->qualifyColumn('tanggal');

        $query
            ->when($bulan, fn (Builder $builder) => $builder->whereMonth($tanggal, $bulan))
            ->when($tahun, fn (Builder $builder) => $builder->whereYear($tanggal, $tahun));
    }

    public function getTotalBiayaAttribute(): float
    {
        return (float) $this->sangu_supir
            + (float) $this->terpal
            + (float) $this->operasional
            + (float) ($this->gajiTelly?->gaji_bersih ?? 0)
            + (float) ($this->paguyuban?->total_bayar ?? 0);
    }

    public function getLabaKotorAttribute(): float
    {
        return (float) $this->pendapatan - $this->total_biaya;
    }

    public function getTotalLapanganAttribute(): float
    {
        return (float) $this->sangu_supir
            + (float) $this->terpal
            + (float) $this->operasional;
    }
}
