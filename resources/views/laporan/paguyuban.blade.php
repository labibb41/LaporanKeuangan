<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Paguyuban</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Biaya paguyuban berdasarkan tonase</h2>
        </div>
    </x-slot>

    @php
        $rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    @endphp

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="flex flex-wrap items-end gap-3 border-b border-stone-200/70 pb-5">
            <form method="GET" action="{{ route('laporan.paguyuban') }}" class="flex flex-wrap items-end gap-3 w-full">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="min-w-[16rem] flex-1">
                    <label for="nama_paguyuban" class="mb-2 block text-sm font-semibold text-stone-700">Pilih Paguyuban</label>
                    <select id="nama_paguyuban" name="nama_paguyuban" class="field bg-white">
                        <option value="">Semua paguyuban (rekap)</option>
                        @foreach ($daftarPaguyuban as $paguyuban)
                            <option value="{{ $paguyuban }}" @selected($namaPaguyuban === $paguyuban)>{{ $paguyuban }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary btn-compact">Tampilkan</button>

                @if ($namaPaguyuban)
                    <a href="{{ route('laporan.paguyuban', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-soft btn-compact">Reset</a>
                @endif
            </form>
        </div>

        @if ($namaPaguyuban)
            <div>
                <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Paguyuban: {{ $namaPaguyuban }}</p>
                        <h3 class="mt-2 text-xl font-black text-stone-950">Daftar Kapal Tergabung</h3>
                    </div>
                    <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">{{ $kapalPaguyuban->count() }} kapal</span>
                </div>

                <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Nama Kapal</th>
                                <th class="px-4 py-3 font-semibold text-right">Kapasitas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 bg-white">
                            @forelse ($kapalPaguyuban as $kapal)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-stone-900">{{ $kapal->nama_kapal }}</td>
                                    <td class="px-4 py-3 text-right text-stone-600">{{ $kapal->kapasitas_ton ? number_format((float) $kapal->kapasitas_ton, 0, ',', '.') . ' ton' : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-6 text-center text-stone-500">Belum ada kapal yang terdaftar di paguyuban ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-8">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Detail Transaksi</p>
                        <h3 class="mt-2 text-xl font-black text-stone-950">Biaya Paguyuban</h3>
                    </div>
                    <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">{{ $detail->count() }} transaksi</span>
                </div>

                <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Tanggal</th>
                                <th class="px-4 py-3 font-semibold">Kapal</th>
                                <th class="px-4 py-3 font-semibold">Kendaraan</th>
                                <th class="px-4 py-3 font-semibold text-right">Tonase</th>
                                <th class="px-4 py-3 font-semibold text-right">Total (Tarif {{ $rupiah($tarifPaguyuban) }})</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 bg-white">
                            @forelse ($detail as $item)
                                <tr>
                                    <td class="px-4 py-3 text-stone-600">{{ $item->tanggal->format('d M Y') }}</td>
                                    <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->kapal->nama_kapal }}</td>
                                    <td class="px-4 py-3 text-stone-600">{{ $item->kendaraan->nopol }}</td>
                                    <td class="px-4 py-3 text-right text-stone-600">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-semibold text-stone-900 text-right">{{ $rupiah($item->paguyuban?->total_bayar ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-stone-500">Belum ada transaksi operasional.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($detail->count() > 0)
                            <tfoot class="bg-stone-50 border-t border-stone-200">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 font-bold text-stone-900 text-right">TOTAL</td>
                                    <td class="px-4 py-3 font-bold text-stone-900 text-right">{{ number_format((float) $detail->sum('tonase'), 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-bold text-sky-700 text-right">{{ $rupiah($detail->sum(fn($t) => (float) ($t->paguyuban?->total_bayar ?? 0))) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        @else
            <div>
                <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Ringkasan</p>
                        <h3 class="mt-2 text-xl font-black text-stone-950">Rekap Biaya per Paguyuban</h3>
                    </div>
                </div>

                <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Paguyuban</th>
                                <th class="px-4 py-3 font-semibold text-center">Jumlah Kapal</th>
                                <th class="px-4 py-3 font-semibold text-center">Transaksi</th>
                                <th class="px-4 py-3 font-semibold text-right">Tonase</th>
                                <th class="px-4 py-3 font-semibold text-right">Total Dibayar</th>
                                <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 bg-white">
                            @forelse ($laporan as $item)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->nama_paguyuban }}</td>
                                    <td class="px-4 py-3 text-center text-stone-600">{{ $item->total_kapal }} kapal</td>
                                    <td class="px-4 py-3 text-center text-stone-600">{{ $item->total_transaksi }}x</td>
                                    <td class="px-4 py-3 text-right text-stone-600">{{ number_format((float) $item->total_tonase, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-sky-700">{{ $rupiah($item->total_bayar) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('laporan.paguyuban', ['bulan' => $bulan, 'tahun' => $tahun, 'nama_paguyuban' => $item->nama_paguyuban]) }}" class="rounded-full bg-stone-100 px-3 py-2 text-xs font-semibold text-stone-700 hover:bg-sky-100 hover:text-sky-700">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-stone-500">Belum ada data paguyuban.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>
</x-app-layout>
