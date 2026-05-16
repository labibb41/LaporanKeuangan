<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pemilik;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    public function index(): View
    {
        return view('kendaraan.index', [
            'kendaraan' => Kendaraan::query()
                ->with('pemilik')
                ->withCount('transaksiOperasional')
                ->latest()
                ->paginate(10),
            'daftarPemilik' => Pemilik::query()->orderBy('nama_pemilik')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nopol' => ['required', 'string', 'max:255', 'unique:kendaraan,nopol'],
            'nama_pemilik' => ['required', 'string', 'max:255'],
        ]);

        $pemilik = Pemilik::firstOrCreate(['nama_pemilik' => $validated['nama_pemilik']]);

        Kendaraan::create([
            'nopol' => $validated['nopol'],
            'pemilik_id' => $pemilik->id,
        ]);

        return back()->with('status', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan): View
    {
        return view('kendaraan.edit', [
            'kendaraan' => $kendaraan,
            'daftarPemilik' => Pemilik::query()->orderBy('nama_pemilik')->get(),
        ]);
    }

    public function update(Request $request, Kendaraan $kendaraan): RedirectResponse
    {
        $validated = $request->validate([
            'nopol' => ['required', 'string', 'max:255', Rule::unique('kendaraan', 'nopol')->ignore($kendaraan->id)],
            'nama_pemilik' => ['required', 'string', 'max:255'],
        ]);

        $pemilik = Pemilik::firstOrCreate(['nama_pemilik' => $validated['nama_pemilik']]);

        $kendaraan->update([
            'nopol' => $validated['nopol'],
            'pemilik_id' => $pemilik->id,
        ]);

        return redirect()->route('kendaraan.index')->with('status', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan): RedirectResponse
    {
        if ($kendaraan->transaksiOperasional()->exists()) {
            return back()->withErrors([
                'delete' => 'Kendaraan tidak bisa dihapus karena sudah dipakai di transaksi operasional.',
            ]);
        }

        $kendaraan->delete();

        return back()->with('status', 'Kendaraan berhasil dihapus.');
    }
}
