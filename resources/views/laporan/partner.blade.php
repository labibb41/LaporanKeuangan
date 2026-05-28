<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Partner</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Rincian Pembayaran Rekanan</h2>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="flex flex-wrap items-end gap-3 border-b border-stone-200/70 pb-5">
            <form method="GET" action="{{ route('laporan.partner') }}" class="flex flex-wrap items-end gap-3 w-full">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="min-w-[16rem] flex-1">
                    <label for="pemilik_id" class="mb-2 block text-sm font-semibold text-stone-700">Pilih Rekanan (Pemilik)</label>
                    <select id="pemilik_id" name="pemilik_id" class="field-white">
                        <option value="">Semua rekanan (rekap)</option>
                        @foreach ($daftarPemilik as $pemilik)
                            <option value="{{ $pemilik->id }}" @selected((int) $pemilikId === (int) $pemilik->id)>{{ $pemilik->nama_pemilik }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary btn-compact">Tampilkan</button>

                @if ($pemilikId)
                    <a href="{{ route('laporan.partner', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-soft btn-compact">Reset</a>
                @endif
            </form>
        </div>

        @if ($pemilikId)
            <div>
                <div class="flex flex-wrap items-end justify-between gap-4 mb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Rekanan</p>
                        <h3 class="mt-2 text-xl font-black text-stone-950">{{ $selectedPemilik?->nama_pemilik ?? 'Tidak ditemukan' }}</h3>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-[1.5rem] border border-stone-100">
                    <table class="min-w-full divide-y divide-stone-100 text-sm whitespace-nowrap">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">NOPOL</th>
                                <th class="px-4 py-3 font-semibold">RUTE</th>
                                <th class="px-4 py-3 font-semibold text-right">TONNASE</th>
                                <th class="px-4 py-3 font-semibold text-right">RIT</th>
                                <th class="px-4 py-3 font-semibold text-right">PENDAPATAN BRUTO</th>
                                <th class="px-4 py-3 font-semibold text-right">SANGU SUPIR</th>
                                <th class="px-4 py-3 font-semibold text-right">TERPAL</th>
                                <th class="px-4 py-3 font-semibold text-right">JUMLAH BERSIH</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 bg-white">
                            @forelse ($detail as $item)
                                <tr>
                                    <td class="px-4 py-3 font-bold text-stone-900">{{ $item->kendaraan->nopol }}</td>
                                    <td class="px-4 py-3 text-stone-600">{{ $item->rute }}</td>
                                    <td class="px-4 py-3 text-right text-stone-600">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-stone-600">{{ (int) $item->ritase }}</td>
                                    <td class="px-4 py-3 text-right text-stone-900 font-semibold">{{ $rupiah($item->pendapatan) }}</td>
                                    <td class="px-4 py-3 text-right text-rose-600">{{ $rupiah($item->sangu_supir) }}</td>
                                    <td class="px-4 py-3 text-right text-rose-600">{{ $rupiah($item->terpal) }}</td>
                                    @php($jumlahBersih = $item->pendapatan - $item->sangu_supir - $item->terpal)
                                    <td class="px-4 py-3 text-right font-bold {{ $jumlahBersih >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $rupiah($jumlahBersih) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-stone-500">Belum ada rincian pembayaran untuk rekanan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($detail->isNotEmpty())
                            <tfoot class="bg-stone-50 text-stone-700">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold" colspan="2">TOTAL</th>
                                    <th class="px-4 py-3 text-right font-semibold">{{ number_format((float) $detail->sum('tonase'), 2, ',', '.') }}</th>
                                    <th class="px-4 py-3 text-right font-semibold">{{ (int) $detail->sum('ritase') }}</th>
                                    <th class="px-4 py-3 text-right font-semibold">{{ $rupiah($detail->sum('pendapatan')) }}</th>
                                    <th class="px-4 py-3 text-right font-semibold text-rose-700">{{ $rupiah($detail->sum('sangu_supir')) }}</th>
                                    <th class="px-4 py-3 text-right font-semibold text-rose-700">{{ $rupiah($detail->sum('terpal')) }}</th>
                                    @php($totalBersih = $detail->sum('pendapatan') - $detail->sum('sangu_supir') - $detail->sum('terpal'))
                                    <th class="px-4 py-3 text-right font-black text-emerald-800">{{ $rupiah($totalBersih) }}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        @else
            <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Pemilik</th>
                            <th class="px-4 py-3 font-semibold">Kendaraan</th>
                            <th class="px-4 py-3 font-semibold text-center">Transaksi</th>
                            <th class="px-4 py-3 font-semibold text-right">Pendapatan</th>
                            <th class="px-4 py-3 font-semibold text-right">Biaya</th>
                            <th class="px-4 py-3 font-semibold text-right">Pendapatan Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($laporan as $item)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->nama_pemilik }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->total_kendaraan }} unit</td>
                                <td class="px-4 py-3 text-center text-stone-600">{{ $item->total_transaksi }}x</td>
                                <td class="px-4 py-3 text-right font-semibold text-stone-900">{{ $rupiah($item->total_pendapatan) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-rose-600">{{ $rupiah($item->total_biaya) }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ $item->laba_bersih >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $rupiah($item->laba_bersih) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-stone-500">Belum ada data partner pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-app-layout>
