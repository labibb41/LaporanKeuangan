<?php

namespace App\Http\Controllers;

use App\Models\GajiTelly;
use App\Models\Kapal;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\Paguyuban;
use App\Models\Pemilik;
use App\Models\Pengeluaran;
use App\Models\TransaksiOperasional;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $transaksiBulanan = TransaksiOperasional::query()->periode($bulan, $tahun);
        $pengeluaranBulanan = Pengeluaran::query()->periode($bulan, $tahun);

        $pendapatan = (float) (clone $transaksiBulanan)->sum('pendapatan');
        $biayaSupir = (float) (clone $transaksiBulanan)->sum('sangu_supir');
        $biayaTerpal = (float) (clone $transaksiBulanan)->sum('terpal');
        $biayaOperasional = (float) (clone $transaksiBulanan)->sum('operasional');
        $biayaGajiTelly = (float) GajiTelly::query()
            ->whereHas('transaksi', fn ($query) => $query->periode($bulan, $tahun))
            ->sum('gaji_bersih');
        $biayaPaguyuban = (float) Paguyuban::query()
            ->whereHas('transaksi', fn ($query) => $query->periode($bulan, $tahun))
            ->sum('total_bayar');
        $pengeluaranLain = (float) (clone $pengeluaranBulanan)->sum('jumlah');
        $jumlahTransaksi = (clone $transaksiBulanan)->count();
        $jumlahPengeluaran = (clone $pengeluaranBulanan)->count();
        $totalRitase = (int) (clone $transaksiBulanan)->sum('ritase');
        $totalTonase = (float) (clone $transaksiBulanan)->sum('tonase');
        $totalBiayaOperasional = $biayaSupir + $biayaTerpal + $biayaOperasional + $biayaGajiTelly + $biayaPaguyuban;
        $totalPengeluaran = $totalBiayaOperasional + $pengeluaranLain;
        $labaBersih = $pendapatan - $totalPengeluaran;

        $ringkasanMaster = [
            ['label' => 'Pemilik', 'total' => Pemilik::query()->count(), 'route' => route('pemilik.index')],
            ['label' => 'Kendaraan', 'total' => Kendaraan::query()->count(), 'route' => route('kendaraan.index')],
            ['label' => 'Kapal', 'total' => Kapal::query()->count(), 'route' => route('kapal.index')],
            ['label' => 'Karyawan', 'total' => Karyawan::query()->count(), 'route' => route('karyawan.index')],
        ];

        $menuLaporan = [
            ['label' => 'Pusat laporan', 'route' => route('laporan.index')],
            ['label' => 'Laporan operasional', 'route' => route('operasional.index', ['bulan' => $bulan, 'tahun' => $tahun])],
            ['label' => 'Laporan partner', 'route' => route('laporan.partner', ['bulan' => $bulan, 'tahun' => $tahun])],
            ['label' => 'Laporan laba rugi', 'route' => route('laporan.keuangan', ['bulan' => $bulan, 'tahun' => $tahun])],
        ];

        $kapalTeratas = TransaksiOperasional::query()
            ->join('kapal', 'kapal.id', '=', 'transaksi_operasional.kapal_id')
            ->leftJoin('gaji_telly', 'gaji_telly.transaksi_id', '=', 'transaksi_operasional.id')
            ->leftJoin('paguyuban', 'paguyuban.transaksi_id', '=', 'transaksi_operasional.id')
            ->select('kapal.nama_kapal')
            ->selectRaw('SUM(transaksi_operasional.pendapatan) as total_pendapatan')
            ->selectRaw('SUM(transaksi_operasional.sangu_supir + transaksi_operasional.terpal + transaksi_operasional.operasional + COALESCE(gaji_telly.gaji_bersih, 0) + COALESCE(paguyuban.total_bayar, 0)) as total_biaya')
            ->periode($bulan, $tahun)
            ->groupBy('kapal.id', 'kapal.nama_kapal')
            ->orderByDesc('total_pendapatan')
            ->limit(5)
            ->get();

        $partnerTeratas = Pemilik::query()
            ->withCount('kendaraan')
            ->with([
                'kendaraan.transaksiOperasional' => fn ($query) => $query
                    ->periode($bulan, $tahun)
                    ->with(['gajiTelly', 'paguyuban']),
            ])
            ->get()
            ->map(function (Pemilik $pemilik) {
                $transaksi = $pemilik->kendaraan->flatMap->transaksiOperasional;
                $pendapatanPemilik = (float) $transaksi->sum('pendapatan');
                $biayaPemilik = (float) $transaksi->sum(fn (TransaksiOperasional $item) => $item->total_biaya);

                return (object) [
                    'nama_pemilik' => $pemilik->nama_pemilik,
                    'kendaraan_count' => $pemilik->kendaraan_count,
                    'total_pendapatan' => $pendapatanPemilik,
                    'total_biaya' => $biayaPemilik,
                    'laba_bersih' => $pendapatanPemilik - $biayaPemilik,
                ];
            })
            ->filter(fn ($item) => $item->total_pendapatan > 0 || $item->total_biaya > 0)
            ->sortByDesc('laba_bersih')
            ->take(5)
            ->values();

        $tellyTeratas = GajiTelly::query()
            ->join('karyawan', 'karyawan.id', '=', 'gaji_telly.karyawan_id')
            ->join('transaksi_operasional', 'transaksi_operasional.id', '=', 'gaji_telly.transaksi_id')
            ->select('karyawan.nama')
            ->selectRaw('COUNT(gaji_telly.id) as total_aktivitas')
            ->selectRaw('SUM(gaji_telly.gaji_bersih) as total_bersih')
            ->whereMonth('transaksi_operasional.tanggal', $bulan)
            ->whereYear('transaksi_operasional.tanggal', $tahun)
            ->groupBy('karyawan.id', 'karyawan.nama')
            ->orderByDesc('total_bersih')
            ->limit(5)
            ->get();

        $transaksiTerbaru = TransaksiOperasional::query()
            ->with(['kapal', 'kendaraan.pemilik', 'gajiTelly', 'paguyuban'])
            ->latest('tanggal')
            ->limit(6)
            ->get();

        $pengeluaranTerbaru = Pengeluaran::query()
            ->latest('tanggal')
            ->limit(6)
            ->get();

        return view('dashboard', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pendapatan' => $pendapatan,
            'biayaSupir' => $biayaSupir,
            'biayaTerpal' => $biayaTerpal,
            'biayaOperasional' => $biayaOperasional,
            'biayaGajiTelly' => $biayaGajiTelly,
            'biayaPaguyuban' => $biayaPaguyuban,
            'pengeluaranLain' => $pengeluaranLain,
            'totalBiayaOperasional' => $totalBiayaOperasional,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
            'jumlahTransaksi' => $jumlahTransaksi,
            'jumlahPengeluaran' => $jumlahPengeluaran,
            'totalRitase' => $totalRitase,
            'totalTonase' => $totalTonase,
            'ringkasanMaster' => $ringkasanMaster,
            'menuLaporan' => $menuLaporan,
            'kapalTeratas' => $kapalTeratas,
            'partnerTeratas' => $partnerTeratas,
            'tellyTeratas' => $tellyTeratas,
            'transaksiTerbaru' => $transaksiTerbaru,
            'pengeluaranTerbaru' => $pengeluaranTerbaru,
        ]);
    }

    private function period(Request $request): array
    {
        $bulan = max(1, min(12, (int) $request->integer('bulan', now()->month)));
        $tahun = max(2020, (int) $request->integer('tahun', now()->year));

        return [$bulan, $tahun];
    }
}
