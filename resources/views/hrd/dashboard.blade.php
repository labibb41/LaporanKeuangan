<x-hrd-layout>
    <x-slot name="header">
        <p class="page-label">Portal HRD</p>
        <h2 class="page-title" style="font-family:'Montserrat',sans-serif;">Dashboard Ringkasan</h2>
        <p class="page-sub">Rekap data bulan <strong>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</strong></p>
    </x-slot>

    @php
        $rupiah = fn($n) => 'Rp ' . number_format((float)$n, 0, ',', '.');
        $months = range(1,12);
    @endphp

    {{-- Period Filter --}}
    <form method="GET" action="{{ route('hrd.dashboard') }}"
          class="mb-5 flex flex-wrap items-end gap-3 card">
        <div>
            <label class="label">Bulan</label>
            <select name="bulan" class="field w-28">
                @foreach($months as $m)
                    <option value="{{ $m }}" @selected($m == $bulan)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Tahun</label>
            <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="{{ now()->year }}" class="field w-28">
        </div>
        <button type="submit" class="btn-primary btn-compact">Terapkan</button>
    </form>

    {{-- ── Stat Cards ── --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Total Gaji Bersih --}}
        <div class="rounded-xl p-4 text-white shadow-sm" style="background: linear-gradient(135deg, #164A41, #0f3830);">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/70">Total Gaji Bersih</p>
            <p class="mt-3 text-2xl font-black" style="font-family:'Montserrat',sans-serif;">{{ $rupiah($totalGajiBersih) }}</p>
            <p class="mt-1 text-xs text-white/60">Bulan ini</p>
        </div>

        {{-- Karyawan Aktif --}}
        <div class="stat-card">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-500">Karyawan Aktif</p>
            <p class="mt-3 text-2xl font-black text-slate-900" style="font-family:'Montserrat',sans-serif;">{{ $karyawanAktif }}</p>
            <p class="mt-1 text-xs text-slate-400">orang terdaftar</p>
        </div>

        {{-- Total Tonase --}}
        <div class="rounded-xl p-4 shadow-sm" style="background: linear-gradient(135deg, #F1B24A, #d4962e);">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em]" style="color: rgba(0,0,0,0.55);">Total Tonase</p>
            <p class="mt-3 text-2xl font-black text-stone-900" style="font-family:'Montserrat',sans-serif;">{{ number_format($totalTonase, 1, ',', '.') }}</p>
            <p class="mt-1 text-xs" style="color: rgba(0,0,0,0.50);">{{ number_format($totalRitase, 0, ',', '.') }} ritase</p>
        </div>

        {{-- Pengeluaran --}}
        <div class="stat-card">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-500">Pengeluaran Lain</p>
            <p class="mt-3 text-2xl font-black text-rose-700" style="font-family:'Montserrat',sans-serif;">{{ $rupiah($totalPengeluaran) }}</p>
            <p class="mt-1 text-xs text-slate-400">Non-operasional</p>
        </div>
    </div>

    {{-- ── Top 5 Karyawan ── --}}
    <div class="mt-5 card">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="section-label">Ringkasan Gaji</p>
                <h3 class="section-title" style="font-family:'Montserrat',sans-serif;">Top Karyawan Bulan Ini</h3>
            </div>
            <a href="{{ route('hrd.gaji', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="btn-compact btn text-xs font-semibold border rounded-lg px-3 py-1.5 transition hover:bg-slate-50"
               style="border-color: #9DC88D; color: #164A41;">
                Lihat Semua →
            </a>
        </div>

        <div class="mt-4 table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Aktivitas</th>
                        <th>Gaji Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topKaryawan as $i => $kar)
                        <tr>
                            <td class="font-bold" style="color: #164A41;">{{ $i + 1 }}</td>
                            <td class="font-semibold text-slate-900">{{ $kar->nama }}</td>
                            <td class="text-slate-500">{{ $kar->jabatan ?: '—' }}</td>
                            <td>{{ $kar->total_aktivitas }} trip</td>
                            <td class="font-bold" style="color: #164A41;">{{ $rupiah($kar->total_gaji_bersih) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state text-slate-400">Belum ada data gaji pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Quick Links ── --}}
    <div class="mt-5 grid gap-4 sm:grid-cols-3">
        @foreach([
            ['title' => 'Rekap Gaji Telly', 'desc' => 'Gaji kotor, PPh, dan gaji bersih per karyawan.', 'route' => 'hrd.gaji', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z'],
            ['title' => 'Operasional Kapal', 'desc' => 'Ritase, tonase, dan pendapatan per kapal.', 'route' => 'hrd.operasional', 'icon' => 'M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z'],
            ['title' => 'Laba Rugi', 'desc' => 'Laporan keuangan ringkas per periode.', 'route' => 'hrd.keuangan', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ] as $link)
        <a href="{{ route($link['route'], ['bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="card-hover flex items-start gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0"
                  style="background: #e8f5e0; color: #164A41; width:40px;height:40px;min-width:40px;">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $link['icon'] }}"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-bold text-slate-900" style="font-family:'Montserrat',sans-serif;">{{ $link['title'] }}</p>
                <p class="mt-0.5 text-[11px] text-slate-500">{{ $link['desc'] }}</p>
            </div>
        </a>
        @endforeach
    </div>
</x-hrd-layout>
