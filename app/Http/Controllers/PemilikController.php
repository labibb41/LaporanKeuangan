<?php

namespace App\Http\Controllers;

use App\Models\Pemilik;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    public function index(): View
    {
        return view('pemilik.index', [
            'pemilik' => Pemilik::query()
                ->withCount('kendaraan')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pemilik' => ['required', 'string', 'max:255'],
        ]);

        Pemilik::create($validated);

        return back()->with('status', 'Pemilik berhasil ditambahkan.');
    }

    public function edit(Pemilik $pemilik): View
    {
        return view('pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, Pemilik $pemilik): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pemilik' => ['required', 'string', 'max:255'],
        ]);

        $pemilik->update($validated);

        return redirect()->route('pemilik.index')->with('status', 'Data pemilik berhasil diperbarui.');
    }

    public function destroy(Pemilik $pemilik): RedirectResponse
    {
        if ($pemilik->kendaraan()->exists()) {
            return back()->withErrors([
                'delete' => 'Pemilik tidak bisa dihapus karena masih memiliki kendaraan aktif.',
            ]);
        }

        $pemilik->delete();

        return back()->with('status', 'Pemilik berhasil dihapus.');
    }
}
