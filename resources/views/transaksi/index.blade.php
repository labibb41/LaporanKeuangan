<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="page-label">Database General</p>
                <h2 class="page-title">Data Mentah Operasional</h2>
                <p class="page-sub">Daftar seluruh transaksi operasional yang telah diinput.</p>
            </div>
            <a href="{{ route('transaksi-operasional.create') }}" class="btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Data General
            </a>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <div class="space-y-6">

        {{-- ═══ KPI Summary ═══════════════════════════════════════════ --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="stat-card relative overflow-hidden bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-200">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-white/80">Baris Data</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7a8 3 0 0116 0M4 7v10a8 3 0 0016 0V7"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-3xl font-black leading-none">{{ $summary['total'] }}</p>
                    <p class="mt-2 text-xs text-white/70">Total data tersimpan</p>
                </div>
            </div>

            <div class="stat-card border border-stone-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-stone-500">Total Trips</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100">
                        <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 104 0M5 17a2 2 0 104 0m-4 0h14a2 2 0 002-2v-3a2 2 0 00-2-2h-1l-2-5H6L4 10H3a2 2 0 00-2 2v3a2 2 0 002 2h2"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-3xl font-black text-stone-950 leading-none">{{ $summary['ritase'] }}</p>
                <p class="mt-2 text-xs text-stone-400">Ritase bulan ini</p>
            </div>

            <div class="stat-card border border-stone-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-stone-500">Total Tonnase</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100">
                        <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-3xl font-black text-stone-950 leading-none">{{ number_format($summary['tonase'], 2, ',', '.') }}</p>
                <p class="mt-2 text-xs text-stone-400">Ton diangkut</p>
            </div>

            <div class="stat-card border border-sky-100 bg-sky-50 shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-sky-600">Saku + Terpal</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-100">
                        <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-2xl font-black text-sky-900 leading-none">{{ $rupiah($summary['sangu_supir'] + $summary['terpal']) }}</p>
                <p class="mt-2 text-xs text-sky-600">Total biaya supir & terpal</p>
            </div>
        </section>

        {{-- ═══ Filter ═════════════════════════════════════════════════ --}}
        <section class="card">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex items-center gap-3 mr-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-stone-100">
                        <svg class="h-4 w-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-stone-700 text-sm">Filter Periode</span>
                </div>
                <div>
                    <label for="bulan" class="label">Bulan</label>
                    <input id="bulan" name="bulan" type="number" min="1" max="12"
                        value="{{ $bulan }}" class="field-white w-24">
                </div>
                <div>
                    <label for="tahun" class="label">Tahun</label>
                    <input id="tahun" name="tahun" type="number" min="2020"
                        value="{{ $tahun }}" class="field-white w-28">
                </div>
                <button type="submit" class="btn-primary btn-compact">Terapkan</button>
            </form>
        </section>

        {{-- ═══ Data Table ══════════════════════════════════════════════ --}}
        <section class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kapal</th>
                            <th>Nopol</th>
                            <th class="text-right">Tonnase</th>
                            <th class="text-center">Trips</th>
                            <th>Rute</th>
                            <th>Pemilik</th>
                            <th class="text-right">Saku</th>
                            <th class="text-right">Terpal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $item)
                            <tr>
                                <td class="font-semibold text-stone-900 text-sm whitespace-nowrap">{{ $item->kapal->nama_kapal }}</td>
                                <td>
                                    <span class="badge-stone">{{ $item->kendaraan->nopol }}</span>
                                </td>
                                <td class="text-right text-stone-700 font-medium text-sm">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge-amber">{{ $item->ritase }}</span>
                                </td>
                                <td class="text-stone-600 text-sm">{{ $item->rute }}</td>
                                <td class="text-stone-600 text-sm">{{ $item->kendaraan->pemilik->nama_pemilik }}</td>
                                <td class="text-right text-stone-700 text-sm whitespace-nowrap">{{ $rupiah($item->sangu_supir) }}</td>
                                <td class="text-right text-stone-700 text-sm whitespace-nowrap">{{ $rupiah($item->terpal) }}</td>
                                <td>
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('transaksi-operasional.show', $item) }}"
                                           class="btn-icon bg-sky-50 text-sky-700 hover:bg-sky-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('transaksi-operasional.edit', $item) }}"
                                           class="btn-icon bg-stone-100 text-stone-600 hover:bg-amber-100 hover:text-amber-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('transaksi-operasional.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700"
                                                onclick="return confirm('Hapus data general ini?')">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7a8 3 0 0116 0M4 7v10a8 3 0 0016 0V7"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-stone-500">Belum ada data general tersimpan.</p>
                                        <p class="mt-1 text-xs text-stone-400">Tambahkan data melalui tombol di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transaksi->hasPages())
                <div class="mt-5 border-t border-stone-100 pt-5">
                    {{ $transaksi->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
