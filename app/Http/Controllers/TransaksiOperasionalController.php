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

        return view('transaksi.index', [
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
        ]);
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTransaksi($request);

        $transaksi = DB::transaction(function () use ($validated) {
            $transaksi = TransaksiOperasional::create($this->transactionPayload($validated));
            $this->syncRelasiTambahan($transaksi, $validated);

            return $transaksi;
        });

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

    public function update(Request $request, TransaksiOperasional $transaksiOperasional): RedirectResponse
    {
        $validated = $this->validateTransaksi($request, $transaksiOperasional);

        DB::transaction(function () use ($transaksiOperasional, $validated) {
            $transaksiOperasional->update($this->transactionPayload($validated));
            $this->syncRelasiTambahan($transaksiOperasional, $validated);
        });

        return redirect()->route('transaksi-operasional.show', $transaksiOperasional)->with('status', 'Transaksi operasional berhasil diperbarui.');
    }

    public function destroy(TransaksiOperasional $transaksiOperasional): RedirectResponse
    {
        $transaksiOperasional->delete();

        return redirect()->route('transaksi-operasional.index')->with('status', 'Transaksi operasional berhasil dihapus.');
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
