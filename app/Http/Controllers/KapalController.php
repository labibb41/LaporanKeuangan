<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KapalController extends Controller
{
    public function index(): View
    {
        return view('kapal.index', [
            'kapal' => Kapal::query()
                ->withCount('transaksiOperasional')
                ->latest()
                ->paginate(10),
            'daftarPaguyuban' => Kapal::select('nama_paguyuban')->distinct()->pluck('nama_paguyuban')->filter(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($validated['nama_paguyuban'] === 'NEW') {
            $validated['nama_paguyuban'] = null;
        }

        Kapal::create($validated);

        return back()->with('status', 'Kapal berhasil ditambahkan.');
    }

    public function edit(Kapal $kapal): View
    {
        $daftarPaguyuban = Kapal::select('nama_paguyuban')->distinct()->pluck('nama_paguyuban')->filter();
        return view('kapal.edit', compact('kapal', 'daftarPaguyuban'));
    }

    public function update(Request $request, Kapal $kapal): RedirectResponse
    {
        $validated = $request->validate($this->rules($kapal));

        if ($validated['nama_paguyuban'] === 'NEW') {
            $validated['nama_paguyuban'] = null;
        }

        $kapal->update($validated);

        return redirect()->route('kapal.index')->with('status', 'Data kapal berhasil diperbarui.');
    }

    public function destroy(Kapal $kapal): RedirectResponse
    {
        if ($kapal->transaksiOperasional()->exists()) {
            return back()->withErrors([
                'delete' => 'Kapal tidak bisa dihapus karena sudah dipakai di transaksi operasional.',
            ]);
        }

        $kapal->delete();

        return back()->with('status', 'Kapal berhasil dihapus.');
    }

    private function rules(?Kapal $kapal = null): array
    {
        return [
            'nama_kapal'      => ['required', 'string', 'max:255', Rule::unique('kapal', 'nama_kapal')->ignore($kapal?->id)],
            'nama_paguyuban'  => ['nullable', 'string', 'max:255'],
            'kapasitas_ton'   => ['nullable', 'numeric', 'min:0'],
            'status'          => ['nullable', 'in:aktif,nonaktif'],
            'keterangan'      => ['nullable', 'string'],
            'tarif_tonase'    => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
