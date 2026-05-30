<?php

namespace App\Support;

use App\Models\Kapal;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\OperasionalRekap;
use App\Models\Pemilik;
use App\Models\Pengeluaran;
use App\Models\TransaksiOperasional;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogFormatter
{
    public static function labelFor(Model|string $model): string
    {
        $class = is_string($model) ? $model : $model::class;

        return match ($class) {
            Pemilik::class => 'Pemilik',
            Kendaraan::class => 'Kendaraan',
            Kapal::class => 'Kapal',
            Karyawan::class => 'Karyawan',
            Pengeluaran::class => 'Pengeluaran',
            OperasionalRekap::class => 'Operasional',
            TransaksiOperasional::class => 'Transaksi Operasional',
            User::class => 'Pengguna',
            default => class_basename($class),
        };
    }

    public static function titleFor(Model $model): string
    {
        return match (true) {
            $model instanceof Pemilik => $model->nama_pemilik,
            $model instanceof Kendaraan => $model->nopol,
            $model instanceof Kapal => $model->nama_kapal,
            $model instanceof Karyawan => $model->nama,
            $model instanceof Pengeluaran => $model->nama_kegiatan ?: $model->jenis,
            $model instanceof OperasionalRekap => trim(($model->kapal?->nama_kapal ?? 'Kapal') . ' - ' . ($model->rute ?? 'Operasional')),
            $model instanceof TransaksiOperasional => trim(($model->kapal?->nama_kapal ?? 'Kapal') . ' - ' . ($model->rute ?? 'Transaksi')),
            $model instanceof User => $model->name,
            default => 'Data #' . $model->getKey(),
        };
    }

    public static function actionText(string $action): string
    {
        return match ($action) {
            'created' => 'menambahkan',
            'updated' => 'mengubah',
            'deleted' => 'menghapus',
            default => $action,
        };
    }

    public static function description(Model $model, string $action): string
    {
        $userName = auth()->user()?->name ?? 'Admin';

        return sprintf(
            '%s %s %s: %s',
            $userName,
            self::actionText($action),
            self::labelFor($model),
            self::titleFor($model)
        );
    }
}
