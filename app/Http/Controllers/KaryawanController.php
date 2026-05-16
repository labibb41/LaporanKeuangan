<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        return view('karyawan.index', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'karyawan' => Karyawan::query()
                ->withCount(['transaksiTelly', 'gajiTelly'])
                ->withSum('gajiTelly as total_gaji_kotor', 'gaji_total')
                ->withSum('gajiTelly as total_gaji_bersih', 'gaji_bersih')
                ->withSum([
                    'gajiTelly as total_gaji_kotor_bulanan' => fn ($query) => $query
                        ->whereHas('transaksi', fn ($builder) => $builder->periode($bulan, $tahun)),
                ], 'gaji_total')
                ->withSum([
                    'gajiTelly as total_gaji_bersih_bulanan' => fn ($query) => $query
                        ->whereHas('transaksi', fn ($builder) => $builder->periode($bulan, $tahun)),
                ], 'gaji_bersih')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Karyawan::create($validated);

        return back()->with('status', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan): View
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')->with('status', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        if ($karyawan->transaksiTelly()->exists() || $karyawan->gajiTelly()->exists()) {
            return back()->withErrors([
                'delete' => 'Karyawan tidak bisa dihapus karena masih terkait transaksi atau gaji telly.',
            ]);
        }

        $karyawan->delete();

        return back()->with('status', 'Karyawan berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'nama'              => ['required', 'string', 'max:255'],
            'no_hp'             => ['nullable', 'string', 'max:30'],
            'alamat'            => ['nullable', 'string'],
            'tanggal_bergabung' => ['nullable', 'date'],
            'status'            => ['nullable', 'in:aktif,nonaktif'],
            'jabatan'           => ['nullable', 'string', 'max:255'],
            'ktp'               => ['nullable', 'string', 'max:255'],
            'npwp'              => ['nullable', 'string', 'max:255'],
            'tarif_telly'       => ['nullable', 'numeric', 'min:0'],
            'pph_persen'        => ['nullable', 'numeric', 'min:0'],
        ];
    }

    private function period(Request $request): array
    {
        $bulan = max(1, min(12, (int) $request->integer('bulan', now()->month)));
        $tahun = max(2020, (int) $request->integer('tahun', now()->year));

        return [$bulan, $tahun];
    }
}
