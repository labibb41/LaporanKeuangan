<?php

namespace Database\Seeders;

use App\Models\GajiTelly;
use App\Models\Kapal;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\Paguyuban;
use App\Models\Pemilik;
use App\Models\Pengeluaran;
use App\Models\TransaksiOperasional;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@laporankeuangan.test'],
            [
                'name' => 'Admin Operasional',
                'password' => Hash::make('password'),
            ]
        );

        $pemilik = Pemilik::query()->firstOrCreate([
            'nama_pemilik' => 'PT Samudra Sentosa',
        ]);

        $kapal = Kapal::query()->firstOrCreate([
            'nama_kapal' => 'KM Laut Jaya',
        ]);

        $karyawan = Karyawan::query()->firstOrCreate(
            ['nama' => 'Telly Operasional'],
            [
                'jabatan' => 'Telly',
                'ktp' => '3174XXXXXXXXXXXX',
                'npwp' => '12.345.678.9-000.000',
            ]
        );

        $kendaraan = Kendaraan::query()->firstOrCreate(
            ['nopol' => 'B 9123 LKO'],
            ['pemilik_id' => $pemilik->id]
        );

        $transaksi = TransaksiOperasional::query()->firstOrCreate(
            [
                'tanggal' => now()->toDateString(),
                'kapal_id' => $kapal->id,
                'kendaraan_id' => $kendaraan->id,
                'rute' => 'Pelabuhan Tanjung Priok - Gudang Cakung',
            ],
            [
                'ritase' => 2,
                'tonase' => 28.5,
                'sangu_supir' => 350000,
                'terpal' => 150000,
                'operasional' => 8500000,
                'telly_id' => $karyawan->id,
                'keterangan' => 'Data awal untuk demo dashboard.',
            ]
        );

        GajiTelly::query()->updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'karyawan_id' => $karyawan->id,
                'gaji' => 100000,
                'gaji_total' => 200000,
                'pph' => 5000,
                'gaji_bersih' => 195000,
                'keterangan' => 'Contoh gaji telly',
            ]
        );

        Paguyuban::query()->updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'tanggal' => now()->toDateString(),
                'jumlah_orang' => 20,
                'tarif' => 500,
                'total_bayar' => 10000,
            ]
        );

        Pengeluaran::query()->firstOrCreate(
            [
                'tanggal' => now()->toDateString(),
                'jenis' => 'BBM Operasional',
            ],
            [
                'nama_kegiatan' => 'Pengisian solar armada',
                'jumlah' => 1250000,
                'penerima' => 'SPBU Mitra',
                'keterangan' => 'Contoh data awal pengeluaran',
            ]
        );
    }
}
