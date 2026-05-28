<?php

namespace App\Http\Controllers;

use App\Models\GajiTelly;
use App\Models\Paguyuban;
use App\Models\Pemilik;
use App\Models\Pengeluaran;
use App\Models\TransaksiOperasional;
use App\Models\Karyawan;
use App\Models\Kapal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $transaksi = TransaksiOperasional::query()
            ->with(['gajiTelly', 'paguyuban'])
            ->periode($bulan, $tahun)
            ->get();

        $pendapatan = (float) $transaksi->sum('pendapatan');
        $biayaOperasional = (float) $transaksi->sum(fn (TransaksiOperasional $item) => $item->total_biaya);
        $pengeluaranLain = (float) Pengeluaran::query()->periode($bulan, $tahun)->sum('jumlah');

        return view('laporan.index', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jumlahTransaksi' => $transaksi->count(),
            'totalPendapatan' => $pendapatan,
            'totalBiayaOperasional' => $biayaOperasional,
            'totalPengeluaran' => $biayaOperasional + $pengeluaranLain,
            'labaBersih' => $pendapatan - $biayaOperasional - $pengeluaranLain,
            'daftarLaporan' => [
                [
                    'title' => 'Laporan Operasional',
                    'description' => 'Rekap ritase, tonase, pendapatan, dan biaya per kapal.',
                    'route' => route('operasional.index', ['bulan' => $bulan, 'tahun' => $tahun]),
                ],
                [
                    'title' => 'Laporan Partner',
                    'description' => 'Pendapatan dan biaya bersih per pemilik kendaraan.',
                    'route' => route('laporan.partner', ['bulan' => $bulan, 'tahun' => $tahun]),
                ],
                [
                    'title' => 'Laporan Gaji Telly',
                    'description' => 'Aktivitas telly lengkap dengan PPh dan gaji bersih.',
                    'route' => route('laporan.telly', ['bulan' => $bulan, 'tahun' => $tahun]),
                ],
                [
                    'title' => 'Laporan Paguyuban',
                    'description' => 'Biaya paguyuban otomatis berdasarkan tonase x 500.',
                    'route' => route('laporan.paguyuban', ['bulan' => $bulan, 'tahun' => $tahun]),
                ],
                [
                    'title' => 'Laporan Pengeluaran',
                    'description' => 'Form dan daftar pengeluaran admin dalam satu halaman laporan.',
                    'route' => route('laporan.pengeluaran', ['bulan' => $bulan, 'tahun' => $tahun]),
                ],
                [
                    'title' => 'Laporan Keuangan',
                    'description' => 'Laporan laba rugi bulanan dengan seluruh komponen biaya.',
                    'route' => route('laporan.keuangan', ['bulan' => $bulan, 'tahun' => $tahun]),
                ],
            ],
        ]);
    }

    public function operasional(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $laporan = TransaksiOperasional::query()
            ->join('kapal', 'kapal.id', '=', 'transaksi_operasional.kapal_id')
            ->leftJoin('gaji_telly', 'gaji_telly.transaksi_id', '=', 'transaksi_operasional.id')
            ->leftJoin('paguyuban', 'paguyuban.transaksi_id', '=', 'transaksi_operasional.id')
            ->select('kapal.nama_kapal')
            ->selectRaw('COUNT(transaksi_operasional.id) as total_transaksi')
            ->selectRaw('SUM(transaksi_operasional.ritase) as total_ritase')
            ->selectRaw('SUM(transaksi_operasional.tonase) as total_tonase')
            ->selectRaw('SUM(transaksi_operasional.pendapatan) as total_pendapatan')
            ->selectRaw('SUM(transaksi_operasional.sangu_supir + transaksi_operasional.terpal + transaksi_operasional.operasional + COALESCE(gaji_telly.gaji_bersih, 0) + COALESCE(paguyuban.total_bayar, 0)) as total_biaya')
            ->periode($bulan, $tahun)
            ->groupBy('kapal.id', 'kapal.nama_kapal')
            ->orderBy('kapal.nama_kapal')
            ->get();

        $detailTransaksi = TransaksiOperasional::query()
            ->with(['kapal', 'telly'])
            ->periode($bulan, $tahun)
            ->orderBy('tanggal_kegiatan')
            ->orderBy('tanggal')
            ->get();

        return view('laporan.operasional', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'laporan' => $laporan,
            'detailTransaksi' => $detailTransaksi,
        ]);
    }

    public function partner(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $pemilikId = $request->integer('pemilik_id');
        $daftarPemilik = Pemilik::orderBy('nama_pemilik')->get();

        $laporan = collect();
        $detail = collect();
        $selectedPemilik = null;

        if ($pemilikId) {
            $selectedPemilik = $daftarPemilik->firstWhere('id', $pemilikId);

            $detail = TransaksiOperasional::query()
                ->with(['kapal', 'kendaraan', 'gajiTelly', 'paguyuban'])
                ->whereHas('kendaraan', function ($query) use ($pemilikId) {
                    $query->where('pemilik_id', $pemilikId);
                })
                ->periode($bulan, $tahun)
                ->orderBy('kapal_id')
                ->orderBy('tanggal')
                ->get();
        } else {
            $laporan = Pemilik::query()
                ->withCount('kendaraan')
                ->with([
                    'kendaraan.transaksiOperasional' => fn ($query) => $query
                        ->periode($bulan, $tahun)
                        ->with(['gajiTelly', 'paguyuban']),
                ])
                ->orderBy('nama_pemilik')
                ->get()
                ->map(function (Pemilik $pemilik) {
                    $transaksi = $pemilik->kendaraan->flatMap->transaksiOperasional;
                    $pendapatan = (float) $transaksi->sum('pendapatan');
                    $biaya = (float) $transaksi->sum(fn (TransaksiOperasional $item) => $item->total_biaya);

                    return (object) [
                        'nama_pemilik' => $pemilik->nama_pemilik,
                        'total_kendaraan' => $pemilik->kendaraan_count,
                        'total_transaksi' => $transaksi->count(),
                        'total_pendapatan' => $pendapatan,
                        'total_biaya' => $biaya,
                        'laba_bersih' => $pendapatan - $biaya,
                    ];
                });
        }

        return view('laporan.partner', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pemilikId' => $pemilikId,
            'daftarPemilik' => $daftarPemilik,
            'selectedPemilik' => $selectedPemilik,
            'laporan' => $laporan,
            'detail' => $detail,
        ]);
    }

    public function telly(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $karyawanId = $request->integer('karyawan_id');
        $daftarKaryawan = Karyawan::query()->orderBy('nama')->get();

        $laporan = collect();
        $detail = collect();
        $selectedKaryawan = null;

        if ($karyawanId) {
            $selectedKaryawan = $daftarKaryawan->firstWhere('id', $karyawanId);

            $detail = GajiTelly::query()
                ->join('transaksi_operasional', 'transaksi_operasional.id', '=', 'gaji_telly.transaksi_id')
                ->join('kapal', 'kapal.id', '=', 'transaksi_operasional.kapal_id')
                ->select([
                    'gaji_telly.id as id',
                    'gaji_telly.gaji as gaji',
                    'gaji_telly.gaji_total as gaji_total',
                    'gaji_telly.pph as pph',
                    'gaji_telly.gaji_bersih as gaji_bersih',
                    'gaji_telly.keterangan as keterangan',
                    'transaksi_operasional.tanggal as tanggal',
                    'transaksi_operasional.rute as rute',
                    'transaksi_operasional.ritase as ritase',
                    'transaksi_operasional.tonase as tonase',
                    'kapal.nama_kapal as nama_kapal',
                ])
                ->where('gaji_telly.karyawan_id', $karyawanId)
                ->whereMonth('transaksi_operasional.tanggal', $bulan)
                ->whereYear('transaksi_operasional.tanggal', $tahun)
                ->orderBy('transaksi_operasional.tanggal')
                ->orderBy('kapal.nama_kapal')
                ->get();
        } else {
            $laporan = GajiTelly::query()
                ->join('karyawan', 'karyawan.id', '=', 'gaji_telly.karyawan_id')
                ->join('transaksi_operasional', 'transaksi_operasional.id', '=', 'gaji_telly.transaksi_id')
                ->select('karyawan.nama', 'karyawan.jabatan')
                ->selectRaw('COUNT(gaji_telly.id) as total_aktivitas')
                ->selectRaw('SUM(gaji_telly.gaji_total) as gaji_kotor')
                ->selectRaw('SUM(gaji_telly.pph) as total_pph')
                ->selectRaw('SUM(gaji_telly.gaji_bersih) as gaji_bersih')
                ->whereMonth('transaksi_operasional.tanggal', $bulan)
                ->whereYear('transaksi_operasional.tanggal', $tahun)
                ->groupBy('karyawan.id', 'karyawan.nama', 'karyawan.jabatan')
                ->orderBy('karyawan.nama')
                ->get();
        }

        return view('laporan.telly', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'karyawanId' => $karyawanId,
            'daftarKaryawan' => $daftarKaryawan,
            'selectedKaryawan' => $selectedKaryawan,
            'laporan' => $laporan,
            'detail' => $detail,
        ]);
    }

    public function paguyuban(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $namaPaguyuban = $request->query('nama_paguyuban');
        $daftarPaguyuban = Kapal::select('nama_paguyuban')->whereNotNull('nama_paguyuban')->distinct()->pluck('nama_paguyuban')->filter();

        $laporan = collect();
        $detail = collect();
        $kapalPaguyuban = collect();

        if ($namaPaguyuban) {
            $detail = TransaksiOperasional::query()
                ->with(['kapal', 'kendaraan', 'paguyuban'])
                ->whereHas('kapal', function ($q) use ($namaPaguyuban) {
                    $q->where('nama_paguyuban', $namaPaguyuban);
                })
                ->whereHas('paguyuban')
                ->periode($bulan, $tahun)
                ->orderBy('tanggal')
                ->get();
                
            $kapalPaguyuban = Kapal::where('nama_paguyuban', $namaPaguyuban)->orderBy('nama_kapal')->get();
        } else {
            $laporan = $daftarPaguyuban->map(function ($nama) use ($bulan, $tahun) {
                $transaksi = TransaksiOperasional::query()
                    ->with('paguyuban')
                    ->whereHas('kapal', function ($q) use ($nama) {
                        $q->where('nama_paguyuban', $nama);
                    })
                    ->whereHas('paguyuban')
                    ->periode($bulan, $tahun)
                    ->get();

                return (object) [
                    'nama_paguyuban' => $nama,
                    'total_kapal' => Kapal::where('nama_paguyuban', $nama)->count(),
                    'total_transaksi' => $transaksi->count(),
                    'total_tonase' => $transaksi->sum('tonase'),
                    'total_bayar' => $transaksi->sum(fn ($t) => (float) ($t->paguyuban?->total_bayar ?? 0)),
                ];
            });
        }

        return view('laporan.paguyuban', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'namaPaguyuban' => $namaPaguyuban,
            'daftarPaguyuban' => $daftarPaguyuban,
            'laporan' => $laporan,
            'detail' => $detail,
            'kapalPaguyuban' => $kapalPaguyuban,
            'tarifPaguyuban' => Paguyuban::DEFAULT_TARIF,
        ]);
    }

    public function keuangan(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $transaksi = TransaksiOperasional::query()
            ->with(['gajiTelly', 'paguyuban'])
            ->periode($bulan, $tahun)
            ->get();

        $pengeluaran = Pengeluaran::query()
            ->periode($bulan, $tahun)
            ->orderBy('tanggal')
            ->get();

        $pendapatan = (float) $transaksi->sum('pendapatan');
        $biayaSupir = (float) $transaksi->sum('sangu_supir');
        $biayaTerpal = (float) $transaksi->sum('terpal');
        $biayaOperasional = (float) $transaksi->sum('operasional');
        $biayaTelly = (float) $transaksi->sum(fn (TransaksiOperasional $item) => (float) ($item->gajiTelly?->gaji_bersih ?? 0));
        $biayaPaguyuban = (float) $transaksi->sum(fn (TransaksiOperasional $item) => (float) ($item->paguyuban?->total_bayar ?? 0));
        $pengeluaranLain = (float) $pengeluaran->sum('jumlah');
        $totalBiaya = $biayaSupir + $biayaTerpal + $biayaOperasional + $biayaTelly + $biayaPaguyuban + $pengeluaranLain;

        return view('laporan.keuangan', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'transaksi' => $transaksi,
            'pengeluaran' => $pengeluaran,
            'pendapatan' => $pendapatan,
            'biayaSupir' => $biayaSupir,
            'biayaTerpal' => $biayaTerpal,
            'biayaOperasional' => $biayaOperasional,
            'biayaTelly' => $biayaTelly,
            'biayaPaguyuban' => $biayaPaguyuban,
            'pengeluaranLain' => $pengeluaranLain,
            'totalBiaya' => $totalBiaya,
            'labaBersih' => $pendapatan - $totalBiaya,
        ]);
    }

    private function period(Request $request): array
    {
        $bulan = max(1, min(12, (int) $request->integer('bulan', now()->month)));
        $tahun = max(2020, (int) $request->integer('tahun', now()->year));

        return [$bulan, $tahun];
    }
}
