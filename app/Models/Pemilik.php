<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemilik extends Model
{
    use HasFactory;

    protected $table = 'pemilik';

    protected $fillable = [
        'nama_pemilik',
    ];

    public function kendaraan(): HasMany
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function transaksiOperasional()
    {
        return $this->hasManyThrough(TransaksiOperasional::class, Kendaraan::class);
    }
}
