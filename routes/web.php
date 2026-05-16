<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OperasionalController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiOperasionalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('pemilik', PemilikController::class)->except(['create', 'show']);
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
        Route::get('/operasional', [OperasionalController::class, 'index'])->name('operasional');
        Route::get('/partner', [LaporanController::class, 'partner'])->name('partner');
        Route::get('/telly', [LaporanController::class, 'telly'])->name('telly');
        Route::get('/paguyuban', [LaporanController::class, 'paguyuban'])->name('paguyuban');
        Route::get('/pengeluaran', [PengeluaranController::class, 'report'])->name('pengeluaran');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
    });
});

require __DIR__.'/auth.php';
