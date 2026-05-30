<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Kapal;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\OperasionalRekap;
use App\Models\Pemilik;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Support\ActivityLogFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerActivityLogs();
    }

    private function registerActivityLogs(): void
    {
        $models = [
            Pemilik::class,
            Kendaraan::class,
            Kapal::class,
            Karyawan::class,
            Pengeluaran::class,
            OperasionalRekap::class,
            User::class,
        ];

        foreach ($models as $model) {
            $model::created(fn (Model $record) => $this->writeActivityLog($record, 'created'));
            $model::updated(fn (Model $record) => $this->writeActivityLog($record, 'updated'));
            $model::deleted(fn (Model $record) => $this->writeActivityLog($record, 'deleted'));
        }
    }

    private function writeActivityLog(Model $record, string $action): void
    {
        if (! auth()->check()) {
            return;
        }

        if ($record instanceof User && $record->is(auth()->user())) {
            return;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'model_type' => $record::class,
            'model_id' => $record->getKey(),
            'action' => $action,
            'description' => ActivityLogFormatter::description($record, $action),
        ]);
    }
}
