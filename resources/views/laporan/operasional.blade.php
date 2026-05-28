<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Operasional</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Rekap operasional per kapal</h2>
        </div>
    </x-slot>

    @php
        $rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    @endphp

    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm space-y-6">
        @include('laporan._toolbar')

        <div>
            <div class="mb-4">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Periode {{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}</p>
                <h3 class="mt-2 text-xl font-black text-stone-950">Ringkasan per kapal</h3>
            </div>

            <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Kapal</th>
                            <th class="px-4 py-3 font-semibold">Transaksi</th>
                            <th class="px-4 py-3 font-semibold">Ritase</th>
                            <th class="px-4 py-3 font-semibold">Tonase</th>
                            <th class="px-4 py-3 font-semibold">Pendapatan</th>
                            <th class="px-4 py-3 font-semibold">Biaya</th>
                            <th class="px-4 py-3 font-semibold">Laba</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($laporan as $item)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->nama_kapal }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->total_transaksi }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->total_ritase }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ number_format((float) $item->total_tonase, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->total_pendapatan) }}</td>
                                <td class="px-4 py-3 font-semibold text-stone-700">{{ $rupiah($item->total_biaya) }}</td>
                                <td class="px-4 py-3 font-semibold {{ ($item->total_pendapatan - $item->total_biaya) >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $rupiah($item->total_pendapatan - $item->total_biaya) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-stone-500">Belum ada data operasional pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-t border-stone-200/70 pt-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Detail Operasional</p>
                    <h3 class="mt-2 text-xl font-black text-stone-950">Kolom transaksi sesuai format lapangan</h3>
                </div>
                <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">{{ $detailTransaksi->count() }} transaksi</span>
            </div>

            <div class="overflow-x-auto rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Kapal</th>
                            <th class="px-4 py-3 font-semibold">Rute</th>
                            <th class="px-4 py-3 font-semibold">Trips</th>
                            <th class="px-4 py-3 font-semibold">Tonnase</th>
                            <th class="px-4 py-3 font-semibold">Sangu Supir</th>
                            <th class="px-4 py-3 font-semibold">Terpal</th>
                            <th class="px-4 py-3 font-semibold">Operasional</th>
                            <th class="px-4 py-3 font-semibold">Total</th>
                            <th class="px-4 py-3 font-semibold">Telly</th>
                            <th class="px-4 py-3 font-semibold">Tgl Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($detailTransaksi as $item)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->kapal->nama_kapal }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->rute }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->ritase }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $rupiah($item->sangu_supir) }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $rupiah($item->terpal) }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $rupiah($item->operasional) }}</td>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->total_lapangan) }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->telly?->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ ($item->tanggal_kegiatan ?? $item->tanggal)->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-stone-500">Belum ada detail transaksi operasional pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-app-layout>
