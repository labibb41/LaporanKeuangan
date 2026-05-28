<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HrdController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OperasionalController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiOperasionalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isHrd()
            ? redirect()->route('hrd.dashboard')
            : redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'role:admin'])
    ->name('dashboard');

Route::get('/reset-operasional', function () {
    \App\Models\OperasionalRekap::truncate();
    return redirect()->route('operasional.index')->with('status', 'Data operasional lama berhasil di-reset!');
});

// ── Admin Routes ───────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/api/activity-logs', [TransaksiOperasionalController::class, 'latestActivity'])->name('api.activity-logs');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('pemilik', PemilikController::class)->except(['create']);
    Route::resource('kapal', KapalController::class)->except(['create', 'show']);
    Route::resource('karyawan', KaryawanController::class)->except(['create', 'show']);
    Route::resource('kendaraan', KendaraanController::class)->except(['create', 'show']);
    Route::get('kendaraan/{kendaraan}/info', [TransaksiOperasionalController::class, 'infoKendaraan'])->name('kendaraan.info');
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);
    Route::resource('transaksi-operasional', TransaksiOperasionalController::class)->parameters([
        'transaksi-operasional' => 'transaksiOperasional',
    ]);
    Route::resource('operasional', OperasionalController::class)->except(['show']);

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/partner', [LaporanController::class, 'partner'])->name('partner');
        Route::get('/telly', [LaporanController::class, 'telly'])->name('telly');
        Route::get('/paguyuban', [LaporanController::class, 'paguyuban'])->name('paguyuban');
        Route::get('/pengeluaran', [PengeluaranController::class, 'report'])->name('pengeluaran');
        Route::get('/pengeluaran/cetak', [PengeluaranController::class, 'cetak'])->name('pengeluaran.cetak');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
    });

    // Manajemen Pengguna (admin only)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// ── HRD Portal Routes (read-only) ──────────────────────────
Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/', [HrdController::class, 'dashboard'])->name('dashboard');
    Route::get('/gaji', [HrdController::class, 'gaji'])->name('gaji');
    Route::get('/operasional', [HrdController::class, 'operasional'])->name('operasional');
    Route::get('/keuangan', [HrdController::class, 'keuangan'])->name('keuangan');

    // HRD boleh edit profil sendiri
    Route::get('/profile', [ProfileController::class, 'edit'])->name('hrd.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('hrd.profile.update');
});

require __DIR__.'/auth.php';
