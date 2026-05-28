<?php

namespace App\Http\Controllers;

use App\Models\GajiTelly;
use App\Models\Karyawan;
use App\Models\Kapal;
use App\Models\Pengeluaran;
use App\Models\TransaksiOperasional;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HrdController extends Controller
{
    public function dashboard(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $transaksi = TransaksiOperasional::query()
            ->with(['gajiTelly', 'paguyuban'])
            ->periode($bulan, $tahun)
            ->get();

        $totalGajiBersih = GajiTelly::query()
            ->join('transaksi_operasional', 'transaksi_operasional.id', '=', 'gaji_telly.transaksi_id')
            ->whereMonth('transaksi_operasional.tanggal', $bulan)
            ->whereYear('transaksi_operasional.tanggal', $tahun)
            ->sum('gaji_telly.gaji_bersih');

        $topKaryawan = GajiTelly::query()
            ->join('karyawan', 'karyawan.id', '=', 'gaji_telly.karyawan_id')
            ->join('transaksi_operasional', 'transaksi_operasional.id', '=', 'gaji_telly.transaksi_id')
            ->select('karyawan.nama', 'karyawan.jabatan')
            ->selectRaw('COUNT(gaji_telly.id) as total_aktivitas')
            ->selectRaw('SUM(gaji_telly.gaji_bersih) as total_gaji_bersih')
            ->whereMonth('transaksi_operasional.tanggal', $bulan)
            ->whereYear('transaksi_operasional.tanggal', $tahun)
            ->groupBy('karyawan.id', 'karyawan.nama', 'karyawan.jabatan')
            ->orderByDesc('total_gaji_bersih')
            ->limit(5)
            ->get();

        return view('hrd.dashboard', [
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'totalGajiBersih' => (float) $totalGajiBersih,
            'karyawanAktif'   => Karyawan::where('status', 'aktif')->count(),
            'totalTonase'     => (float) $transaksi->sum('tonase'),
            'totalRitase'     => (int) $transaksi->sum('ritase'),
            'totalPengeluaran'=> (float) Pengeluaran::query()->periode($bulan, $tahun)->sum('jumlah'),
            'topKaryawan'     => $topKaryawan,
        ]);
    }

    public function gaji(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $karyawanId = $request->integer('karyawan_id');
        $daftarKaryawan = Karyawan::orderBy('nama')->get();
        $ringkasan = collect();
        $detail = collect();
        $selectedKaryawan = null;

        if ($karyawanId) {
            $selectedKaryawan = $daftarKaryawan->firstWhere('id', $karyawanId);
            $detail = GajiTelly::query()
                ->join('transaksi_operasional', 'transaksi_operasional.id', '=', 'gaji_telly.transaksi_id')
                ->join('kapal', 'kapal.id', '=', 'transaksi_operasional.kapal_id')
                ->select([
                    'gaji_telly.gaji_total as gaji_total',
                    'gaji_telly.pph as pph',
                    'gaji_telly.gaji_bersih as gaji_bersih',
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
                ->get();
        } else {
            $ringkasan = GajiTelly::query()
                ->join('karyawan', 'karyawan.id', '=', 'gaji_telly.karyawan_id')
                ->join('transaksi_operasional', 'transaksi_operasional.id', '=', 'gaji_telly.transaksi_id')
                ->select('karyawan.nama', 'karyawan.jabatan', 'karyawan.id as karyawan_id')
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

        return view('hrd.gaji', [
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'karyawanId'      => $karyawanId,
            'daftarKaryawan'  => $daftarKaryawan,
            'selectedKaryawan'=> $selectedKaryawan,
            'ringkasan'       => $ringkasan,
            'detail'          => $detail,
        ]);
    }

    public function operasional(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $perKapal = TransaksiOperasional::query()
            ->join('kapal', 'kapal.id', '=', 'transaksi_operasional.kapal_id')
            ->select('kapal.nama_kapal')
            ->selectRaw('COUNT(transaksi_operasional.id) as total_transaksi')
            ->selectRaw('SUM(transaksi_operasional.ritase) as total_ritase')
            ->selectRaw('SUM(transaksi_operasional.tonase) as total_tonase')
            ->selectRaw('SUM(transaksi_operasional.pendapatan) as total_pendapatan')
            ->periode($bulan, $tahun)
            ->groupBy('kapal.id', 'kapal.nama_kapal')
            ->orderBy('kapal.nama_kapal')
            ->get();

        $totalTonase  = (float) $perKapal->sum('total_tonase');
        $totalRitase  = (int)   $perKapal->sum('total_ritase');
        $totalPendapatan = (float) $perKapal->sum('total_pendapatan');

        return view('hrd.operasional', [
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'perKapal'       => $perKapal,
            'totalTonase'    => $totalTonase,
            'totalRitase'    => $totalRitase,
            'totalPendapatan'=> $totalPendapatan,
        ]);
    }

    public function keuangan(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $transaksi = TransaksiOperasional::query()
            ->with(['gajiTelly', 'paguyuban'])
            ->periode($bulan, $tahun)
            ->get();

        $pengeluaran     = Pengeluaran::query()->periode($bulan, $tahun)->orderBy('tanggal')->get();
        $pendapatan      = (float) $transaksi->sum('pendapatan');
        $biayaSupir      = (float) $transaksi->sum('sangu_supir');
        $biayaTerpal     = (float) $transaksi->sum('terpal');
        $biayaOperasional= (float) $transaksi->sum('operasional');
        $biayaTelly      = (float) $transaksi->sum(fn ($t) => (float) ($t->gajiTelly?->gaji_bersih ?? 0));
        $biayaPaguyuban  = (float) $transaksi->sum(fn ($t) => (float) ($t->paguyuban?->total_bayar ?? 0));
        $pengeluaranLain = (float) $pengeluaran->sum('jumlah');
        $totalBiaya      = $biayaSupir + $biayaTerpal + $biayaOperasional + $biayaTelly + $biayaPaguyuban + $pengeluaranLain;

        return view('hrd.keuangan', [
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'pendapatan'      => $pendapatan,
            'biayaSupir'      => $biayaSupir,
            'biayaTerpal'     => $biayaTerpal,
            'biayaOperasional'=> $biayaOperasional,
            'biayaTelly'      => $biayaTelly,
            'biayaPaguyuban'  => $biayaPaguyuban,
            'pengeluaranLain' => $pengeluaranLain,
            'totalBiaya'      => $totalBiaya,
            'labaBersih'      => $pendapatan - $totalBiaya,
            'pengeluaran'     => $pengeluaran,
        ]);
    }

    private function period(Request $request): array
    {
        $bulan = max(1, min(12, (int) $request->integer('bulan', now()->month)));
        $tahun = max(2020, (int) $request->integer('tahun', now()->year));
        return [$bulan, $tahun];
    }
}
