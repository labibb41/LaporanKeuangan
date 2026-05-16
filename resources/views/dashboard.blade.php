<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-5">
            <div>
                <p class="page-label">Dashboard</p>
                <h2 class="page-title">Ringkasan Operasional & Keuangan</h2>
                <p class="page-sub">Semua angka mengikuti filter bulan dan tahun yang dipilih.</p>
            </div>

            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="bulan" class="label">Bulan</label>
                    <input id="bulan" name="bulan" type="number" min="1" max="12" value="{{ $bulan }}"
                        class="field-white w-24" placeholder="1–12">
                </div>
                <div>
                    <label for="tahun" class="label">Tahun</label>
                    <input id="tahun" name="tahun" type="number" min="2020" value="{{ $tahun }}"
                        class="field-white w-28">
                </div>
                <button type="submit" class="btn-primary btn-compact">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Tampilkan
                </button>
            </form>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <div class="space-y-6">

        {{-- ═══ KPI Cards ═════════════════════════════════════════════ --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Pendapatan --}}
            <div class="stat-card relative overflow-hidden bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-200">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-5 -right-5 h-20 w-20 rounded-full bg-white/5"></div>
                <div class="relative">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-white/80">Pendapatan</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-2xl font-black leading-none">{{ $rupiah($pendapatan) }}</p>
                    <p class="mt-2 text-xs text-white/70">Bulan {{ $bulan }}/{{ $tahun }}</p>
                </div>
            </div>

            {{-- Total Pengeluaran --}}
            <div class="stat-card relative overflow-hidden border border-stone-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-stone-500">Total Pengeluaran</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-stone-100">
                        <svg class="h-4 w-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-2xl font-black text-stone-950 leading-none">{{ $rupiah($totalPengeluaran) }}</p>
                <p class="mt-2 text-xs text-stone-400">Bulan {{ $bulan }}/{{ $tahun }}</p>
            </div>

            {{-- Laba Bersih --}}
            <div class="stat-card relative overflow-hidden border {{ $labaBersih >= 0 ? 'border-emerald-200 bg-emerald-50' : 'border-rose-200 bg-rose-50' }} shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] {{ $labaBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">Laba Bersih</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl {{ $labaBersih >= 0 ? 'bg-emerald-100' : 'bg-rose-100' }}">
                        <svg class="h-4 w-4 {{ $labaBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $labaBersih >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-2xl font-black {{ $labaBersih >= 0 ? 'text-emerald-900' : 'text-rose-900' }} leading-none">{{ $rupiah($labaBersih) }}</p>
                <p class="mt-2 text-xs {{ $labaBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $labaBersih >= 0 ? 'Periode menguntungkan' : 'Perlu perhatian' }}</p>
            </div>

            {{-- Aktivitas --}}
            <div class="stat-card relative overflow-hidden border border-indigo-200 bg-indigo-50 shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-indigo-600">Aktivitas</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-100">
                        <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-2xl font-black text-indigo-900 leading-none">{{ $jumlahTransaksi }} <span class="text-base font-semibold text-indigo-600">transaksi</span></p>
                <p class="mt-2 text-xs text-indigo-600">{{ number_format($totalTonase, 2, ',', '.') }} ton · {{ $totalRitase }} ritase</p>
            </div>
        </section>

        {{-- ═══ Quick Access ═══════════════════════════════════════════ --}}
        <section class="card">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="section-label">Akses Cepat</p>
                    <h3 class="section-title">Laporan Utama</h3>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($menuLaporan as $item)
                        <a href="{{ $item['route'] }}" class="btn-soft btn-compact inline-flex items-center gap-2">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v8m16 0H3"/>
                            </svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ═══ Master Summary Cards ════════════════════════════════════ --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($ringkasanMaster as $item)
                <a href="{{ $item['route'] }}"
                   class="group card-hover flex flex-col justify-between gap-3 p-5">
                    <div class="flex items-center justify-between">
                        <p class="section-label">{{ $item['label'] }}</p>
                        <svg class="h-4 w-4 text-stone-300 transition group-hover:text-stone-500 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-stone-950">{{ $item['total'] }}</p>
                </a>
            @endforeach
        </section>

        {{-- ═══ Biaya + Kapal + Partner ════════════════════════════════ --}}
        <section class="grid gap-5 xl:grid-cols-3">

            {{-- Biaya Operasional --}}
            <div class="card">
                <p class="section-label">Biaya Operasional</p>
                <h3 class="section-title text-base">Rincian pengeluaran</h3>
                <div class="mt-5 space-y-2.5 text-sm">
                    @foreach ([
                        ['label' => 'Sangu supir',          'value' => $biayaSupir],
                        ['label' => 'Terpal',               'value' => $biayaTerpal],
                        ['label' => 'Biaya operasional',    'value' => $biayaOperasional],
                        ['label' => 'Gaji telly',           'value' => $biayaGajiTelly],
                        ['label' => 'Paguyuban',            'value' => $biayaPaguyuban],
                    ] as $baris)
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-stone-600">{{ $baris['label'] }}</span>
                            <span class="font-semibold text-stone-800">{{ $rupiah($baris['value']) }}</span>
                        </div>
                    @endforeach

                    <div class="!mt-4 border-t border-stone-100 pt-3">
                        <div class="flex items-center justify-between gap-3 font-bold text-stone-900">
                            <span>Total biaya operasional</span>
                            <span>{{ $rupiah($totalBiayaOperasional) }}</span>
                        </div>
                        <div class="mt-1.5 flex items-center justify-between gap-3 font-semibold text-rose-700">
                            <span>Pengeluaran lain</span>
                            <span>{{ $rupiah($pengeluaranLain) }}</span>
                        </div>
                    </div>

                    <div class="!mt-3 rounded-xl bg-stone-950 px-4 py-3">
                        <div class="flex items-center justify-between gap-3 font-black text-amber-300">
                            <span>Total pengeluaran</span>
                            <span>{{ $rupiah($totalPengeluaran) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kapal Teratas --}}
            <div class="card">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="section-label">Kapal Teratas</p>
                        <h3 class="section-title text-base">Kontribusi per kapal</h3>
                    </div>
                    <a href="{{ route('laporan.operasional', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                       class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50 transition">
                        Lihat
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="mt-5 space-y-2.5">
                    @forelse ($kapalTeratas as $idx => $item)
                        <div class="rounded-2xl border border-stone-100 bg-stone-50/80 px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-stone-200 text-xs font-bold text-stone-600">{{ $idx + 1 }}</span>
                                <p class="font-semibold text-stone-900">{{ $item->nama_kapal }}</p>
                            </div>
                            <div class="mt-2 flex justify-between gap-3 text-xs text-stone-500">
                                <span class="flex items-center gap-1">
                                    <span class="inline-block h-2 w-2 rounded-full bg-sky-400"></span>
                                    {{ $rupiah($item->total_pendapatan) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="inline-block h-2 w-2 rounded-full bg-rose-400"></span>
                                    {{ $rupiah($item->total_biaya) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/></svg>
                            </div>
                            <p class="text-sm font-medium text-stone-500">Belum ada transaksi pada periode ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Partner Teratas --}}
            <div class="card">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="section-label">Partner Teratas</p>
                        <h3 class="section-title text-base">Pendapatan bersih pemilik</h3>
                    </div>
                    <a href="{{ route('laporan.partner', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                       class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50 transition">
                        Lihat
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="mt-5 space-y-2.5">
                    @forelse ($partnerTeratas as $idx => $item)
                        <div class="rounded-2xl border border-stone-100 bg-stone-50/80 px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-xs font-bold text-amber-700">{{ $idx + 1 }}</span>
                                <p class="font-semibold text-stone-900">{{ $item->nama_pemilik }}</p>
                            </div>
                            <div class="mt-2 flex justify-between gap-3 text-xs text-stone-500">
                                <span>{{ $item->kendaraan_count }} kendaraan</span>
                                <span class="font-semibold text-emerald-700">{{ $rupiah($item->laba_bersih) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
                            </div>
                            <p class="text-sm font-medium text-stone-500">Belum ada data partner aktif.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ═══ Transaksi & Pengeluaran Terbaru ═══════════════════════ --}}
        <section class="grid gap-5 xl:grid-cols-2">

            {{-- Transaksi Terbaru --}}
            <div class="card">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="section-label">Transaksi Terbaru</p>
                        <h3 class="section-title text-base">Operasional terakhir</h3>
                    </div>
                    <a href="{{ route('transaksi-operasional.create') }}" class="btn-primary btn-compact inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </a>
                </div>

                <div class="mt-5 table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Armada</th>
                                <th>Pendapatan</th>
                                <th>Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksiTerbaru as $item)
                                <tr>
                                    <td class="text-xs text-stone-500 whitespace-nowrap">{{ $item->tanggal->format('d M Y') }}</td>
                                    <td>
                                        <p class="font-semibold text-stone-900 text-xs">{{ $item->kapal->nama_kapal }}</p>
                                        <p class="text-xs text-stone-400">{{ $item->kendaraan->nopol }} · {{ $item->kendaraan->pemilik->nama_pemilik }}</p>
                                    </td>
                                    <td class="font-semibold text-stone-800 text-xs whitespace-nowrap">{{ $rupiah($item->pendapatan) }}</td>
                                    <td class="font-semibold text-xs whitespace-nowrap {{ $item->laba_kotor >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $rupiah($item->laba_kotor) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <p class="text-sm text-stone-400">Belum ada transaksi tersimpan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pengeluaran & Gaji Telly --}}
            <div class="space-y-5">

                {{-- Pengeluaran Terbaru --}}
                <div class="card">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="section-label">Pengeluaran Terbaru</p>
                            <h3 class="section-title text-base">Biaya perusahaan</h3>
                        </div>
                        <a href="{{ route('pengeluaran.create') }}" class="btn-compact inline-flex items-center gap-1.5 rounded-2xl bg-amber-500 px-3 py-2 text-sm font-semibold text-stone-900 hover:bg-amber-400 transition">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <div class="mt-5 space-y-2.5">
                        @forelse ($pengeluaranTerbaru as $item)
                            <div class="flex items-center justify-between gap-3 rounded-xl bg-stone-50 px-4 py-3">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900 text-sm">{{ $item->jenis }}</p>
                                    <p class="text-xs text-stone-400">{{ $item->tanggal->format('d M Y') }}{{ $item->penerima ? ' · ' . $item->penerima : '' }}</p>
                                </div>
                                <p class="shrink-0 font-semibold text-rose-700 text-sm">{{ $rupiah($item->jumlah) }}</p>
                            </div>
                        @empty
                            <p class="rounded-xl bg-stone-50 px-4 py-5 text-center text-sm text-stone-400">Belum ada pengeluaran dicatat.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Gaji Telly Teratas --}}
                <div class="card">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="section-label">Gaji Telly</p>
                            <h3 class="section-title text-base">Aktivitas telly teratas</h3>
                        </div>
                        <a href="{{ route('laporan.telly', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                           class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50 transition">
                            Lihat semua
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="mt-5 space-y-2.5">
                        @forelse ($tellyTeratas as $item)
                            <div class="flex items-center justify-between gap-3 rounded-xl bg-stone-50 px-4 py-3">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900 text-sm">{{ $item->nama }}</p>
                                    <p class="text-xs text-stone-400">{{ $item->total_aktivitas }} aktivitas</p>
                                </div>
                                <p class="shrink-0 font-semibold text-stone-800 text-sm">{{ $rupiah($item->total_bersih) }}</p>
                            </div>
                        @empty
                            <p class="rounded-xl bg-stone-50 px-4 py-5 text-center text-sm text-stone-400">Belum ada data telly pada periode ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
