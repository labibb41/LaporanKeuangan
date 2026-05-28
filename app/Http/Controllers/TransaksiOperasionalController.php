<?php

namespace App\Http\Controllers;

use App\Models\GajiTelly;
use App\Models\Kapal;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\Paguyuban;
use App\Models\TransaksiOperasional;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class TransaksiOperasionalController extends Controller
{
    public function index(Request $request): View
    {
        [$bulan, $tahun] = $this->period($request);

        $query = TransaksiOperasional::query()
            ->with(['kapal', 'kendaraan.pemilik', 'telly', 'gajiTelly', 'paguyuban'])
            ->periode($bulan, $tahun)
            ->latest('tanggal');

        $transaksi = $query->paginate(10)->withQueryString();

        $summaryQuery = TransaksiOperasional::query()
            ->periode($bulan, $tahun);

        $biayaGajiTelly = (float) GajiTelly::query()
            ->whereHas('transaksi', fn ($builder) => $builder->periode($bulan, $tahun))
            ->sum('gaji_bersih');

        $biayaPaguyuban = (float) Paguyuban::query()
            ->whereHas('transaksi', fn ($builder) => $builder->periode($bulan, $tahun))
            ->sum('total_bayar');

        $pendapatan = (float) (clone $summaryQuery)->sum('pendapatan');
        $sanguSupir = (float) (clone $summaryQuery)->sum('sangu_supir');
        $terpal = (float) (clone $summaryQuery)->sum('terpal');
        $biayaOperasional = (float) (clone $summaryQuery)->sum('operasional');

        return view('transaksi.index', array_merge([
            'transaksi' => $transaksi,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'summary' => [
                'pendapatan' => $pendapatan,
                'sangu_supir' => $sanguSupir,
                'terpal' => $terpal,
                'biaya_operasional' => $biayaOperasional,
                'gaji_telly' => $biayaGajiTelly,
                'paguyuban' => $biayaPaguyuban,
                'total_biaya' => $sanguSupir + $terpal + $biayaOperasional + $biayaGajiTelly + $biayaPaguyuban,
                'ritase' => (int) (clone $summaryQuery)->sum('ritase'),
                'tonase' => (float) (clone $summaryQuery)->sum('tonase'),
                'total' => (clone $summaryQuery)->count(),
            ],
        ], $this->formData()));
    }

    public function create(): View
    {
        return view('transaksi.create', $this->formData());
    }

    public function infoKendaraan(\App\Models\Kendaraan $kendaraan): JsonResponse
    {
        $kendaraan->load('pemilik');

        return response()->json([
            'id'          => $kendaraan->id,
            'nopol'       => $kendaraan->nopol,
            'pemilik_id'  => $kendaraan->pemilik?->id,
            'nama_pemilik' => $kendaraan->pemilik?->nama_pemilik ?? '-',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransaksi($request);

        $transaksi = DB::transaction(function () use ($validated) {
            $transaksi = TransaksiOperasional::create($this->transactionPayload($validated));
            $this->syncRelasiTambahan($transaksi, $validated);

            return $transaksi;
        });

        if ($request->wantsJson()) {
            $transaksi->load(['kapal', 'kendaraan.pemilik', 'telly', 'gajiTelly.karyawan', 'paguyuban']);
            return response()->json([
                'success' => true,
                'message' => 'Transaksi operasional berhasil disimpan.',
                'transaksi' => $transaksi
            ]);
        }

        return redirect()->route('transaksi-operasional.show', $transaksi)->with('status', 'Transaksi operasional berhasil disimpan.');
    }

    public function show(TransaksiOperasional $transaksiOperasional): View
    {
        $transaksiOperasional->load(['kapal', 'kendaraan.pemilik', 'telly', 'gajiTelly.karyawan', 'paguyuban']);

        return view('transaksi.show', [
            'transaksi' => $transaksiOperasional,
        ]);
    }

    public function edit(TransaksiOperasional $transaksiOperasional): View
    {
        $transaksiOperasional->load(['gajiTelly', 'paguyuban']);

        return view('transaksi.edit', array_merge(
            $this->formData(),
            ['transaksi' => $transaksiOperasional]
        ));
    }

    public function update(Request $request, TransaksiOperasional $transaksiOperasional)
    {
        $dbVersion = (int) $transaksiOperasional->version;
        $submittedVersion = (int) $request->input('version');

        if ($submittedVersion !== $dbVersion) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gagal diperbarui karena telah diubah oleh admin lain. Silakan refresh halaman.',
                    'errors' => [
                        'version' => ['Data ini telah diubah oleh admin lain. Harap refresh halaman untuk memuat data terbaru.']
                    ]
                ], 422);
            }

            return back()->withErrors([
                'version' => 'Data gagal diperbarui karena telah diubah oleh admin lain. Silakan refresh halaman.'
            ]);
        }

        $validated = $this->validateTransaksi($request, $transaksiOperasional);

        DB::transaction(function () use ($transaksiOperasional, $validated) {
            $payload = $this->transactionPayload($validated);
            $payload['version'] = $transaksiOperasional->version + 1;
            $transaksiOperasional->update($payload);
            $this->syncRelasiTambahan($transaksiOperasional, $validated);
        });

        if ($request->wantsJson()) {
            $transaksiOperasional->load(['kapal', 'kendaraan.pemilik', 'telly', 'gajiTelly.karyawan', 'paguyuban']);
            return response()->json([
                'success' => true,
                'message' => 'Transaksi operasional berhasil diperbarui.',
                'transaksi' => $transaksiOperasional
            ]);
        }

        return redirect()->route('transaksi-operasional.show', $transaksiOperasional)->with('status', 'Transaksi operasional berhasil diperbarui.');
    }

    public function destroy(TransaksiOperasional $transaksiOperasional): RedirectResponse
    {
        $transaksiOperasional->delete();

        return redirect()->route('transaksi-operasional.index')->with('status', 'Transaksi operasional berhasil dihapus.');
    }

    public function latestActivity(Request $request): JsonResponse
    {
        $since = $request->query('since');

        $query = \App\Models\ActivityLog::query()
            ->with('user')
            ->where('user_id', '!=', auth()->id())
            ->latest();

        if ($since) {
            try {
                $query->where('created_at', '>', \Carbon\Carbon::parse($since));
            } catch (\Exception $e) {
                $query->where('created_at', '>', now()->subSeconds(15));
            }
        } else {
            // Default: only fetch logs created in the last 15 seconds to avoid flooding old notifications on initial load
            $query->where('created_at', '>', now()->subSeconds(15));
        }

        $logs = $query->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'description' => $log->description,
                'action' => $log->action,
                'user_name' => $log->user->name ?? 'Admin',
                'created_at' => $log->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'logs' => $logs,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    private function formData(): array
    {
        return [
            'daftarKapal' => Kapal::query()->orderBy('nama_kapal')->get(),
            'daftarKendaraan' => Kendaraan::query()->with('pemilik')->orderBy('nopol')->get(),
            'daftarKaryawan' => Karyawan::query()->orderBy('nama')->get(),
            'tarifPaguyuban' => Paguyuban::DEFAULT_TARIF,
        ];
    }

    private function validateTransaksi(Request $request, ?TransaksiOperasional $current = null): array
    {
        $validator = Validator::make($request->all(), [
            'tanggal'          => ['required', 'date'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'kapal_id'         => ['required', 'exists:kapal,id'],
            'kendaraan_id'     => [
                'required',
                'exists:kendaraan,id',
                \Illuminate\Validation\Rule::unique('transaksi_operasional')
                    ->where(fn ($q) => $q
                        ->where('tanggal', $request->tanggal)
                        ->where('kapal_id', $request->kapal_id))
                    ->ignore($current?->id),
            ],
            'rute'             => ['required', 'string', 'max:255'],
            'ritase'           => ['required', 'integer', 'min:1'],
            'tonase'           => ['required', 'numeric', 'min:0'],
            'pendapatan'       => ['nullable', 'numeric', 'min:0'],
            'sangu_supir'      => ['nullable', 'numeric', 'min:0'],
            'terpal'           => ['nullable', 'numeric', 'min:0'],
            'telly_id'         => ['nullable', 'exists:karyawan,id'],
            'keterangan'       => ['nullable', 'string'],

            'gaji'             => ['nullable', 'numeric', 'min:0'],
            'gaji_total'       => ['nullable', 'numeric', 'min:0'],
            'pph'              => ['nullable', 'numeric', 'min:0'],
            'gaji_bersih'      => ['nullable', 'numeric', 'min:0'],
            'gaji_keterangan'  => ['nullable', 'string'],
        ]);

        $validator->after(function ($validator) {
            // Friendly error message for duplicate
            if ($validator->errors()->has('kendaraan_id')) {
                $validator->errors()->forget('kendaraan_id');
                $validator->errors()->add(
                    'kendaraan_id',
                    'Data dengan tanggal, kapal, dan kendaraan yang sama sudah ada. Cek kembali input Anda.'
                );
            }
        });

        return $validator->validate();
    }

    private function transactionPayload(array $validated): array
    {
        return [
            'tanggal' => $validated['tanggal'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
            'kapal_id' => $validated['kapal_id'],
            'kendaraan_id' => $validated['kendaraan_id'],
            'rute' => $validated['rute'],
            'ritase' => $validated['ritase'],
            'tonase' => $validated['tonase'],
            'pendapatan' => $validated['pendapatan'] ?? 0,
            'sangu_supir' => $validated['sangu_supir'] ?? 0,
            'terpal' => $validated['terpal'] ?? 0,
            'operasional' => 0,
            'telly_id' => $validated['telly_id'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ];
    }

    private function syncRelasiTambahan(TransaksiOperasional $transaksi, array $validated): void
    {
        $this->syncGajiTelly($transaksi, $validated);
        $this->syncPaguyuban($transaksi, $validated);
    }

    private function syncGajiTelly(TransaksiOperasional $transaksi, array $validated): void
    {
        if (! $transaksi->telly_id) {
            $transaksi->gajiTelly()->delete();

            return;
        }

        $karyawan = Karyawan::query()->find($transaksi->telly_id);
        $tarifKapal = (float) Kapal::query()
            ->whereKey($transaksi->kapal_id)
            ->value('tarif_tonase');
        $gaji = $validated['gaji'] ?? ($tarifKapal > 0 ? $tarifKapal : ($karyawan?->tarif_telly ?? 0));
        $gajiTotal = $validated['gaji_total'] ?? null;
        $pph = $validated['pph'] ?? null;
        $gajiBersih = $validated['gaji_bersih'] ?? null;
        $keterangan = $validated['gaji_keterangan'] ?? null;

        $gaji = (float) $gaji;
        $tonase = (float) $transaksi->tonase;
        $gajiTotal = $gajiTotal !== null
            ? (float) $gajiTotal
            : ($tarifKapal > 0 ? $gaji * $tonase : $gaji * (int) $transaksi->ritase);

        if ($pph === null) {
            $pph = $gajiTotal * ((float) ($karyawan?->pph_persen ?? 0) / 100);
        }

        $pph = (float) $pph;
        $gajiBersih = $gajiBersih !== null ? (float) $gajiBersih : max(0, $gajiTotal - $pph);

        GajiTelly::updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'karyawan_id' => $transaksi->telly_id,
                'gaji' => $gaji,
                'gaji_total' => $gajiTotal,
                'pph' => $pph,
                'gaji_bersih' => $gajiBersih,
                'keterangan' => $keterangan,
            ]
        );
    }

    private function syncPaguyuban(TransaksiOperasional $transaksi, array $validated): void
    {
        if ((float) $transaksi->tonase <= 0) {
            $transaksi->paguyuban()->delete();

            return;
        }

        Paguyuban::updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'tanggal' => $transaksi->tanggal,
                'jumlah_orang' => null,
                'tarif' => Paguyuban::DEFAULT_TARIF,
                'total_bayar' => (float) $transaksi->tonase * Paguyuban::DEFAULT_TARIF,
            ]
        );
    }

    private function period(Request $request): array
    {
        $bulan = max(1, min(12, (int) $request->integer('bulan', now()->month)));
        $tahun = max(2020, (int) $request->integer('tahun', now()->year));

        return [$bulan, $tahun];
    }
}
