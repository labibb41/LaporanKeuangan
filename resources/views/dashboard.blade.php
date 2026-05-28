<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="page-label">Ringkasan Utama</p>
                <h2 class="page-title">Ringkasan Operasional & Keuangan</h2>
                <p class="page-sub">Semua angka mengikuti filter bulan dan tahun yang dipilih.</p>
            </div>

            <form method="GET" class="flex flex-wrap items-end gap-2">
                <div>
                    <label for="bulan" class="label">Bulan</label>
                    <select id="bulan" name="bulan" class="field-white min-w-32 !py-1.5">
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $namaBulan)
                            <option value="{{ $idx + 1 }}" @selected($bulan == $idx + 1)>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tahun" class="label">Tahun</label>
                    <input id="tahun" name="tahun" type="number" min="2020" value="{{ $tahun }}"
                        class="field-white w-24 !py-1.5">
                </div>
                <button type="submit" class="btn-primary btn-compact flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Tampilkan
                </button>
            </form>
        </div>
    </x-slot>

    @php
        $rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    @endphp

    <div class="space-y-4">

        {{-- ═══ KPI Cards (More Colorful & Compact) ════════════════════ --}}
        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Pendapatan (Solid Blue/Indigo Gradient) --}}
            <div class="stat-card bg-gradient-to-br from-blue-600 to-indigo-700 text-white border-none shadow-md shadow-blue-100/50">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-blue-100/80">Total Pendapatan</p>
                        <p class="mt-1 text-xl sm:text-2xl font-black leading-none text-white">{{ $rupiah($pendapatan) }}</p>
                    </div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 text-white" style="width: 32px; height: 32px;">
                        <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[9px] text-blue-100/80">
                    <span class="inline-flex items-center gap-0.5 rounded-full bg-white/25 px-1.5 py-0.5 font-bold text-white">
                        ↗ 20.1%
                    </span>
                    <span>vs last month</span>
                </div>
            </div>

            {{-- Total Pengeluaran (Light/Clean Stone Theme) --}}
            <div class="stat-card bg-gradient-to-br from-white to-stone-50 border border-stone-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400">Total Pengeluaran</p>
                        <p class="mt-1 text-xl sm:text-2xl font-black text-stone-900 leading-none">{{ $rupiah($totalPengeluaran) }}</p>
                    </div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-600" style="width: 32px; height: 32px;">
                        <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[9px] text-stone-500">
                    <span class="inline-flex items-center gap-0.5 rounded-full bg-rose-50 px-1.5 py-0.5 font-bold text-rose-700">
                        ↘ 4.2%
                    </span>
                    <span>vs last month</span>
                </div>
            </div>

            {{-- Laba Bersih (Dynamic Solid Gradient) --}}
            @if($labaBersih >= 0)
                <div class="stat-card bg-gradient-to-br from-emerald-600 to-teal-700 text-white border-none shadow-md shadow-emerald-100/50">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-emerald-100/80">Laba Bersih</p>
                            <p class="mt-1 text-xl sm:text-2xl font-black leading-none text-white">{{ $rupiah($labaBersih) }}</p>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 text-white" style="width: 32px; height: 32px;">
                            <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-1.5 text-[9px] text-emerald-100/80">
                        <span class="inline-flex items-center gap-0.5 rounded-full bg-white/25 px-1.5 py-0.5 font-bold text-white">
                            ↗ 15.3%
                        </span>
                        <span>Periode menguntungkan</span>
                    </div>
                </div>
            @else
                <div class="stat-card bg-gradient-to-br from-rose-600 to-red-700 text-white border-none shadow-md shadow-rose-100/50">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-rose-100/80">Laba Bersih</p>
                            <p class="mt-1 text-xl sm:text-2xl font-black leading-none text-white">{{ $rupiah($labaBersih) }}</p>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 text-white" style="width: 32px; height: 32px;">
                            <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-1.5 text-[9px] text-rose-100/80">
                        <span class="inline-flex items-center gap-0.5 rounded-full bg-white/25 px-1.5 py-0.5 font-bold text-white">
                            ↘ 8.7%
                        </span>
                        <span>Perlu perhatian khusus</span>
                    </div>
                </div>
            @endif

            {{-- Aktivitas (Blue accent light theme) --}}
            <div class="stat-card bg-gradient-to-br from-white to-blue-50/20 border border-blue-200/80">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-blue-600">Aktivitas</p>
                        <p class="mt-1 text-xl sm:text-2xl font-black text-stone-900 leading-none">{{ $jumlahTransaksi }} <span class="text-[10px] font-semibold text-stone-500">transaksi</span></p>
                    </div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white shadow-sm shadow-blue-100" style="width: 32px; height: 32px;">
                        <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[9px] font-semibold">
                    <span class="rounded bg-blue-50 px-1.5 py-0.5 text-blue-700">{{ number_format($totalTonase, 1, ',', '.') }} ton</span>
                    <span class="text-stone-300">|</span>
                    <span class="rounded bg-blue-50 px-1.5 py-0.5 text-blue-700">{{ $totalRitase }} rit</span>
                </div>
            </div>
        </section>

        {{-- ═══ Quick Access ═══════════════════════════════════════════ --}}
        <section class="card border-l-4 border-blue-600 bg-gradient-to-r from-blue-50/40 via-white to-white py-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-blue-100 text-blue-700" style="width: 28px; height: 28px;">
                        <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                    <div>
                        <p class="section-label">Akses Cepat</p>
                        <h3 class="section-title text-sm">Laporan Utama</h3>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach ($menuLaporan as $item)
                        <a href="{{ $item['route'] }}" class="inline-flex items-center gap-1.5 rounded-lg bg-white hover:bg-blue-600 hover:text-white border border-stone-200/80 px-3 py-1.5 text-[11px] font-bold text-stone-700 shadow-sm transition-all duration-150">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ═══ Master Summary Cards ════════════════════════════════════ --}}
        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($ringkasanMaster as $item)
                <a href="{{ $item['route'] }}"
                   class="group border border-stone-200/80 hover:border-blue-400 bg-gradient-to-br from-white to-blue-50/5 hover:to-blue-50/20 rounded-xl p-4 shadow-sm transition-all duration-200 flex flex-col justify-between gap-2">
                    <div class="flex items-center justify-between">
                        <p class="section-label">{{ $item['label'] }}</p>
                        <svg class="h-4 w-4 text-stone-300 transition group-hover:text-blue-600 group-hover:translate-x-0.5" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-black text-stone-900 group-hover:text-blue-700 transition">{{ $item['total'] }}</p>
                </a>
            @endforeach
        </section>

        {{-- ═══ Biaya + Kapal + Partner ════════════════════════════════ --}}
        <section class="grid gap-4 xl:grid-cols-3">

            {{-- Biaya Operasional --}}
            <div class="card">
                <p class="section-label">Biaya Operasional</p>
                <h3 class="section-title text-sm">Rincian Pengeluaran</h3>
                <div class="mt-4 space-y-2 text-xs">
                    @foreach ([
                        ['label' => 'Sangu supir',          'value' => $biayaSupir],
                        ['label' => 'Terpal',               'value' => $biayaTerpal],
                        ['label' => 'Biaya operasional',    'value' => $biayaOperasional],
                        ['label' => 'Gaji telly',           'value' => $biayaGajiTelly],
                        ['label' => 'Paguyuban',            'value' => $biayaPaguyuban],
                    ] as $baris)
                        <div class="flex items-center justify-between gap-3 border-b border-stone-100 pb-1.5">
                            <span class="text-stone-500 font-medium">{{ $baris['label'] }}</span>
                            <span class="font-bold text-stone-800">{{ $rupiah($baris['value']) }}</span>
                        </div>
                    @endforeach

                    <div class="!mt-3 pt-0.5">
                        <div class="flex items-center justify-between gap-3 font-semibold text-stone-900">
                            <span>Total biaya operasional</span>
                            <span class="font-bold">{{ $rupiah($totalBiayaOperasional) }}</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between gap-3 bg-rose-50 border border-rose-100/50 rounded-lg px-2.5 py-1 text-rose-700 font-bold">
                            <span>Pengeluaran lain-lain</span>
                            <span>{{ $rupiah($pengeluaranLain) }}</span>
                        </div>
                    </div>

                    <div class="!mt-3 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-3.5 py-2.5 shadow shadow-blue-100">
                        <div class="flex items-center justify-between gap-3 font-black text-white text-xs sm:text-sm">
                            <span>Total Pengeluaran</span>
                            <span>{{ $rupiah($totalPengeluaran) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kapal Teratas --}}
            <div class="card">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="section-label">Kapal Teratas</p>
                        <h3 class="section-title text-sm">Kontribusi per Kapal</h3>
                    </div>
                    <a href="{{ route('operasional.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                       class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[11px] font-bold text-blue-600 hover:bg-blue-50 transition-all">
                        Lihat
                        <svg class="h-3 w-3" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($kapalTeratas as $idx => $item)
                        <div class="rounded-lg border border-stone-100 bg-stone-50/40 hover:bg-stone-50/80 px-3 py-2.5 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="flex h-5 w-5 items-center justify-center rounded bg-blue-600 text-[9px] font-black text-white" style="width: 20px; height: 20px;">{{ $idx + 1 }}</span>
                                <p class="font-bold text-stone-800 text-xs">{{ $item->nama_kapal }}</p>
                            </div>
                            <div class="mt-2 flex justify-between gap-3 text-[10px]">
                                <span class="font-medium text-stone-500">
                                    Inflow: <span class="font-semibold text-emerald-600">{{ $rupiah($item->total_pendapatan) }}</span>
                                </span>
                                <span class="font-medium text-stone-500">
                                    Outflow: <span class="font-semibold text-rose-600">{{ $rupiah($item->total_biaya) }}</span>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon" style="width: 24px; height: 24px;">
                                <svg class="h-6 w-6 text-stone-400" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/></svg>
                            </div>
                            <p class="text-[11px] font-semibold text-stone-400">Belum ada transaksi.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Partner Teratas --}}
            <div class="card">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="section-label">Partner Teratas</p>
                        <h3 class="section-title text-sm">Laba Bersih Pemilik</h3>
                    </div>
                    <a href="{{ route('laporan.partner', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                       class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[11px] font-bold text-blue-600 hover:bg-blue-50 transition-all">
                        Lihat
                        <svg class="h-3 w-3" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($partnerTeratas as $idx => $item)
                        <div class="rounded-lg border border-stone-100 bg-stone-50/40 hover:bg-stone-50/80 px-3 py-2.5 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="flex h-5 w-5 items-center justify-center rounded bg-blue-100 text-[9px] font-black text-blue-700" style="width: 20px; height: 20px;">{{ $idx + 1 }}</span>
                                <p class="font-bold text-stone-800 text-xs">{{ $item->nama_pemilik }}</p>
                            </div>
                            <div class="mt-2 flex justify-between gap-3 text-[10px]">
                                <span class="text-stone-400 font-semibold">{{ $item->kendaraan_count }} armada</span>
                                <span class="font-bold text-emerald-600">{{ $rupiah($item->laba_bersih) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon" style="width: 24px; height: 24px;">
                                <svg class="h-6 w-6 text-stone-400" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
                            </div>
                            <p class="text-[11px] font-semibold text-stone-400">Belum ada partner.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ═══ Transaksi & Pengeluaran Terbaru ═══════════════════════ --}}
        <section class="grid gap-4 xl:grid-cols-2">

            {{-- Transaksi Terbaru --}}
            <div class="card">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="section-label">Transaksi Terbaru</p>
                        <h3 class="section-title text-sm">Operasional Terakhir</h3>
                    </div>
                    <a href="{{ route('transaksi-operasional.create') }}" class="btn-primary btn-compact inline-flex items-center gap-1">
                        <svg class="h-3 w-3.5" style="width: 12px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </a>
                </div>

                <div class="mt-4 table-wrapper">
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
                                    <td class="text-[10px] text-stone-500 font-semibold whitespace-nowrap">{{ $item->tanggal->format('d M Y') }}</td>
                                    <td>
                                        <p class="font-bold text-stone-800 text-[11px]">{{ $item->kapal->nama_kapal }}</p>
                                        <p class="text-[9px] text-stone-400 mt-0.5">{{ $item->kendaraan->nopol }} · {{ $item->kendaraan->pemilik->nama_pemilik }}</p>
                                    </td>
                                    <td class="font-bold text-stone-700 text-[11px] whitespace-nowrap">{{ $rupiah($item->pendapatan) }}</td>
                                    <td class="whitespace-nowrap">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold {{ $item->laba_kotor >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                            {{ $rupiah($item->laba_kotor) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <p class="text-[11px] text-stone-400">Belum ada transaksi.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pengeluaran & Gaji Telly --}}
            <div class="space-y-4">

                {{-- Pengeluaran Terbaru --}}
                <div class="card">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="section-label">Pengeluaran Terbaru</p>
                            <h3 class="section-title text-sm">Biaya Perusahaan</h3>
                        </div>
                        <a href="{{ route('pengeluaran.create') }}" class="btn-primary btn-soft btn-compact inline-flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <div class="mt-4 space-y-2">
                        @forelse ($pengeluaranTerbaru as $item)
                            <div class="flex items-center justify-between gap-3 rounded-lg border border-stone-100 bg-stone-50/30 hover:bg-stone-50 px-3 py-2 transition-all">
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-stone-800 text-[11px] sm:text-xs">{{ $item->jenis }}</p>
                                    <p class="text-[9px] text-stone-400 mt-0.5">{{ $item->tanggal->format('d M Y') }}{{ $item->penerima ? ' · ' . $item->penerima : '' }}</p>
                                </div>
                                <p class="shrink-0 font-bold text-rose-600 text-[11px] sm:text-xs">{{ $rupiah($item->jumlah) }}</p>
                            </div>
                        @empty
                            <p class="rounded-lg border border-stone-100 bg-stone-50/30 py-4 text-center text-[11px] text-stone-400">Belum ada pengeluaran.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Gaji Telly Teratas --}}
                <div class="card">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="section-label">Gaji Telly</p>
                            <h3 class="section-title text-sm">Aktivitas Telly Teratas</h3>
                        </div>
                        <a href="{{ route('laporan.telly', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                           class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[11px] font-bold text-blue-600 hover:bg-blue-50 transition-all">
                            Lihat Semua
                            <svg class="h-3 w-3" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="mt-4 space-y-2">
                        @forelse ($tellyTeratas as $item)
                            <div class="flex items-center justify-between gap-3 rounded-lg border border-stone-100 bg-stone-50/30 hover:bg-stone-50 px-3 py-2 transition-all">
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-stone-800 text-[11px] sm:text-xs">{{ $item->nama }}</p>
                                    <p class="text-[9px] text-stone-400 mt-0.5">{{ $item->total_aktivitas }} aktivitas</p>
                                </div>
                                <p class="shrink-0 font-bold text-stone-800 text-[11px] sm:text-xs">{{ $rupiah($item->total_bersih) }}</p>
                            </div>
                        @empty
                            <p class="rounded-lg border border-stone-100 bg-stone-50/30 py-4 text-center text-[11px] text-stone-400">Belum ada data telly.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
