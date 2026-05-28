<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $pengeluaran = Pengeluaran::query()
            ->periode($bulan, $tahun)
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        $total = (float) Pengeluaran::query()
            ->periode($bulan, $tahun)
            ->sum('jumlah');

        return view('pengeluaran.index', compact('pengeluaran', 'bulan', 'tahun', 'total'));
    }

    public function report(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);
        $formPengeluaran = null;

        if ($request->filled('edit')) {
            $formPengeluaran = Pengeluaran::query()->find($request->integer('edit'));
        }

        $pengeluaran = Pengeluaran::query()
            ->periode($bulan, $tahun)
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        $total = (float) Pengeluaran::query()
            ->periode($bulan, $tahun)
            ->sum('jumlah');

        return view('laporan.pengeluaran', [
            'pengeluaran' => $pengeluaran,
            'formPengeluaran' => $formPengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total' => $total,
            'daftarKaryawan' => \App\Models\Karyawan::orderBy('nama')->get(),
            'daftarJenis' => \App\Models\Pengeluaran::select('jenis')->distinct()->pluck('jenis'),
        ]);
    }

    public function cetak(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $pengeluaran = Pengeluaran::query()
            ->periode($bulan, $tahun)
            ->latest('tanggal')
            ->get();

        $total = (float) Pengeluaran::query()
            ->periode($bulan, $tahun)
            ->sum('jumlah');

        return view('laporan.pengeluaran_cetak', [
            'pengeluaran' => $pengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total' => $total,
        ]);
    }

    public function create(): View
    {
        $daftarKaryawan = \App\Models\Karyawan::orderBy('nama')->get();
        $daftarJenis = \App\Models\Pengeluaran::select('jenis')->distinct()->pluck('jenis');
        return view('pengeluaran.create', compact('daftarKaryawan', 'daftarJenis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePengeluaran($request);
        $redirectTo = $request->input('redirect_to');

        Pengeluaran::create($validated);

        if ($redirectTo === 'laporan') {
            return redirect()
                ->route('laporan.pengeluaran', ['bulan' => date('n', strtotime($validated['tanggal'])), 'tahun' => date('Y', strtotime($validated['tanggal']))])
                ->with('status', 'Pengeluaran berhasil disimpan.');
        }

        return redirect()->route('pengeluaran.index')->with('status', 'Pengeluaran berhasil disimpan.');
    }

    public function edit(Pengeluaran $pengeluaran): View
    {
        $daftarKaryawan = \App\Models\Karyawan::orderBy('nama')->get();
        $daftarJenis = \App\Models\Pengeluaran::select('jenis')->distinct()->pluck('jenis');
        return view('pengeluaran.edit', compact('pengeluaran', 'daftarKaryawan', 'daftarJenis'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        $validated = $this->validatePengeluaran($request);
        $redirectTo = $request->input('redirect_to');

        $pengeluaran->update($validated);

        if ($redirectTo === 'laporan') {
            return redirect()
                ->route('laporan.pengeluaran', ['bulan' => date('n', strtotime($validated['tanggal'])), 'tahun' => date('Y', strtotime($validated['tanggal']))])
                ->with('status', 'Pengeluaran berhasil diperbarui.');
        }

        return redirect()->route('pengeluaran.index')->with('status', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran): RedirectResponse
    {
        $pengeluaran->delete();

        return back()->with('status', 'Pengeluaran berhasil dihapus.');
    }

    private function validatePengeluaran(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'string', 'max:255'],
            'nama_kegiatan' => ['nullable', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'penerima' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);
    }

    private function period(Request $request): array
    {
        $bulan = max(1, min(12, (int) $request->integer('bulan', now()->month)));
        $tahun = max(2020, (int) $request->integer('tahun', now()->year));

        return [$bulan, $tahun];
    }
}
