<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\Karyawan;
use App\Models\OperasionalRekap;
use App\Models\TransaksiOperasional;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class OperasionalController extends Controller
{
    public function index(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $agregatGeneral = $this->generalAggregates($bulan, $tahun);
        $rekap = OperasionalRekap::query()
            ->with(['kapal', 'telly', 'creator', 'updater'])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('kapal_id');

        $kapalIds = $agregatGeneral
            ->keys()
            ->merge($rekap->keys())
            ->unique()
            ->values();

        $barisOperasional = $kapalIds
            ->map(function ($kapalId) use ($agregatGeneral, $rekap) {
                $general = $agregatGeneral->get($kapalId);
                $manual = $rekap->get($kapalId);

                return (object) [
                    'kapal_id' => $kapalId,
                    'kapal_nama' => $manual?->kapal?->nama_kapal ?? $general['kapal_nama'] ?? '-',
                    'rute' => $manual?->rute ?? $general['rute'] ?? '-',
                    'trips' => $manual?->trips ?? $general['trips'] ?? 0,
                    'tonase' => $manual?->tonase ?? $general['tonase'] ?? 0,
                    'sangu_supir' => $manual?->sangu_supir ?? $general['sangu_supir'] ?? 0,
                    'terpal' => $manual?->terpal ?? $general['terpal'] ?? 0,
                    'operasional' => $manual?->operasional ?? 0,
                    'total' => (float) ($manual?->sangu_supir ?? $general['sangu_supir'] ?? 0)
                        + (float) ($manual?->terpal ?? $general['terpal'] ?? 0)
                        + (float) ($manual?->operasional ?? 0),
                    'telly' => $manual?->telly?->nama,
                    'tanggal_kegiatan' => $manual?->tanggal_kegiatan,
                    'keterangan' => $manual?->keterangan,
                    'general_count' => $general['jumlah_baris'] ?? 0,
                    'manual_id' => $manual?->id,
                    'created_by' => $manual?->created_by,
                    'updated_by' => $manual?->updated_by,
                    'created_at' => $manual?->created_at,
                    'updated_at' => $manual?->updated_at,
                    'creator' => $manual?->creator,
                    'updater' => $manual?->updater,
                ];
            })
            ->sortBy('kapal_nama')
            ->values();

        return view('operasional.index', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'barisOperasional' => $barisOperasional,
            'jumlahGeneral' => $agregatGeneral->sum('jumlah_baris'),
        ]);
    }

    public function create(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);
        $kapalId = $request->integer('kapal_id');

        return view('operasional.create', [
            'rekap' => new OperasionalRekap([
                'bulan' => $bulan,
                'tahun' => $tahun,
                ...$this->prefillForKapal($kapalId, $bulan, $tahun),
            ]),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'daftarKapal' => Kapal::query()->orderBy('nama_kapal')->get(),
            'daftarKaryawan' => Karyawan::query()->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRekap($request);

        OperasionalRekap::updateOrCreate(
            [
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'kapal_id' => $validated['kapal_id'],
            ],
            $this->payload($validated)
        );

        return redirect()
            ->route('operasional.index', ['bulan' => $validated['bulan'], 'tahun' => $validated['tahun']])
            ->with('status', 'Data operasional berhasil disimpan.');
    }

    public function edit(OperasionalRekap $operasional): View
    {
        return view('operasional.edit', [
            'rekap' => $operasional,
            'bulan' => $operasional->bulan,
            'tahun' => $operasional->tahun,
            'daftarKapal' => Kapal::query()->orderBy('nama_kapal')->get(),
            'daftarKaryawan' => Karyawan::query()->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, OperasionalRekap $operasional): RedirectResponse
    {
        $validated = $this->validateRekap($request, $operasional);

        $operasional->update($this->payload($validated));

        return redirect()
            ->route('operasional.index', ['bulan' => $validated['bulan'], 'tahun' => $validated['tahun']])
            ->with('status', 'Data operasional berhasil diperbarui.');
    }

    public function destroy(OperasionalRekap $operasional): RedirectResponse
    {
        $bulan = $operasional->bulan;
        $tahun = $operasional->tahun;

        $operasional->delete();

        return redirect()
            ->route('operasional.index', ['bulan' => $bulan, 'tahun' => $tahun])
            ->with('status', 'Data operasional berhasil dihapus.');
    }

    private function validateRekap(Request $request, ?OperasionalRekap $operasional = null): array
    {
        return $request->validate([
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2020'],
            'kapal_id' => [
                'required',
                'exists:kapal,id',
                Rule::unique('operasional_rekap')
                    ->where(fn ($query) => $query
                        ->where('bulan', $request->integer('bulan'))
                        ->where('tahun', $request->integer('tahun')))
                    ->ignore($operasional?->id),
            ],
            'rute' => ['nullable', 'string', 'max:255'],
            'trips' => ['nullable', 'integer', 'min:0'],
            'tonase' => ['nullable', 'numeric', 'min:0'],
            'sangu_supir' => ['nullable', 'numeric', 'min:0'],
            'terpal' => ['nullable', 'numeric', 'min:0'],
            'operasional' => ['nullable', 'numeric', 'min:0'],
            'telly_id' => ['nullable', 'exists:karyawan,id'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string'],
        ]);
    }

    private function payload(array $validated): array
    {
        return [
            'rute' => $validated['rute'] ?? null,
            'trips' => $validated['trips'] ?? 0,
            'tonase' => $validated['tonase'] ?? 0,
            'sangu_supir' => $validated['sangu_supir'] ?? 0,
            'terpal' => $validated['terpal'] ?? 0,
            'operasional' => $validated['operasional'] ?? 0,
            'telly_id' => $validated['telly_id'] ?? null,
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ];
    }

    private function prefillForKapal(?int $kapalId, int $bulan, int $tahun): array
    {
        if (! $kapalId) {
            return [];
        }

        $general = $this->generalAggregates($bulan, $tahun)->get($kapalId);

        if (! $general) {
            return ['kapal_id' => $kapalId];
        }

        return [
            'kapal_id' => $kapalId,
            'rute' => $general['rute'],
            'trips' => $general['trips'],
            'tonase' => $general['tonase'],
            'sangu_supir' => $general['sangu_supir'],
            'terpal' => $general['terpal'],
        ];
    }

    private function generalAggregates(int $bulan, int $tahun): Collection
    {
        return TransaksiOperasional::query()
            ->with(['kapal'])
            ->periode($bulan, $tahun)
            ->get()
            ->groupBy('kapal_id')
            ->map(function (Collection $items) {
                $routes = $items
                    ->pluck('rute')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                return [
                    'kapal_nama' => $items->first()?->kapal?->nama_kapal ?? '-',
                    'rute' => $routes ?: '-',
                    'trips' => (int) $items->sum('ritase'),
                    'tonase' => (float) $items->sum('tonase'),
                    'sangu_supir' => (float) $items->sum('sangu_supir'),
                    'terpal' => (float) $items->sum('terpal'),
                    'jumlah_baris' => $items->count(),
                ];
            });
    }

    private function period(Request $request): array
    {
        $bulan = max(1, min(12, (int) $request->integer('bulan', now()->month)));
        $tahun = max(2020, (int) $request->integer('tahun', now()->year));

        return [$bulan, $tahun];
    }
}
