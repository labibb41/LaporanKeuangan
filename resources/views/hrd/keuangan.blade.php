<x-hrd-layout>
    <x-slot name="header">
        <p class="page-label">Rekap HRD</p>
        <h2 class="page-title" style="font-family:'Montserrat',sans-serif;">Laporan Laba Rugi</h2>
        <p class="page-sub">Ringkasan keuangan periode <strong>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</strong></p>
    </x-slot>

    @php
        $rupiah = fn($n) => 'Rp ' . number_format((float)$n, 0, ',', '.');
        $months = range(1,12);
    @endphp

    {{-- Filter --}}
    <form method="GET" action="{{ route('hrd.keuangan') }}" class="mb-5 card flex flex-wrap items-end gap-3">
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

    {{-- Top 3 Cards --}}
    <div class="mb-5 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl p-5 text-white shadow-sm" style="background: linear-gradient(135deg, #164A41, #0f3830);">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/70">Total Pendapatan</p>
            <p class="mt-3 text-2xl font-black" style="font-family:'Montserrat',sans-serif;">{{ $rupiah($pendapatan) }}</p>
        </div>
        <div class="stat-card">
            <p class="section-label">Total Biaya</p>
            <p class="mt-3 text-2xl font-black text-slate-900" style="font-family:'Montserrat',sans-serif;">{{ $rupiah($totalBiaya) }}</p>
        </div>
        <div class="rounded-xl p-5 shadow-sm {{ $labaBersih >= 0 ? '' : 'border border-rose-200 bg-rose-50' }}"
             style="{{ $labaBersih >= 0 ? 'background: linear-gradient(135deg, #F1B24A, #d4962e);' : '' }}">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] {{ $labaBersih >= 0 ? '' : 'text-rose-700' }}"
               style="{{ $labaBersih >= 0 ? 'color: rgba(0,0,0,0.55);' : '' }}">
               Laba Bersih
            </p>
            <p class="mt-3 text-2xl font-black {{ $labaBersih >= 0 ? 'text-stone-900' : 'text-rose-800' }}"
               style="font-family:'Montserrat',sans-serif;">
               {{ $rupiah($labaBersih) }}
            </p>
        </div>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        {{-- Breakdown Biaya --}}
        <div class="card">
            <p class="section-label">Rincian</p>
            <h3 class="section-title mb-4" style="font-family:'Montserrat',sans-serif;">Komponen Biaya</h3>

            <div class="space-y-3 text-sm">
                @foreach([
                    ['label' => 'Sangu Supir',         'value' => $biayaSupir,       'color' => '#164A41'],
                    ['label' => 'Terpal',               'value' => $biayaTerpal,      'color' => '#164A41'],
                    ['label' => 'Biaya Operasional',    'value' => $biayaOperasional, 'color' => '#164A41'],
                    ['label' => 'Gaji Telly (Bersih)',  'value' => $biayaTelly,       'color' => '#164A41'],
                    ['label' => 'Paguyuban',            'value' => $biayaPaguyuban,   'color' => '#164A41'],
                    ['label' => 'Pengeluaran Lain',     'value' => $pengeluaranLain,  'color' => '#c0392b'],
                ] as $item)
                    @php $pct = $totalBiaya > 0 ? round($item['value'] / $totalBiaya * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-slate-600">{{ $item['label'] }}</span>
                            <span class="font-semibold text-slate-800">{{ $rupiah($item['value']) }}</span>
                        </div>
                        <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: {{ $item['color'] }};"></div>
                        </div>
                    </div>
                @endforeach
                <div class="flex justify-between border-t border-slate-100 pt-3 text-sm font-black text-slate-900">
                    <span>Total Biaya</span>
                    <span>{{ $rupiah($totalBiaya) }}</span>
                </div>
            </div>
        </div>

        {{-- Pengeluaran Manual --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4 gap-4">
                <div>
                    <p class="section-label">Non-Operasional</p>
                    <h3 class="section-title" style="font-family:'Montserrat',sans-serif;">Pengeluaran Manual</h3>
                </div>
                <span class="badge-red">{{ $pengeluaran->count() }} item</span>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Penerima</th>
                            <th class="text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $item)
                            <tr>
                                <td class="whitespace-nowrap">{{ $item->tanggal->format('d M Y') }}</td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $item->jenis }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $item->nama_kegiatan ?: '—' }}</p>
                                </td>
                                <td class="text-slate-600">{{ $item->penerima ?: '—' }}</td>
                                <td class="text-right font-semibold text-rose-700">{{ $rupiah($item->jumlah) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state text-slate-400">Tidak ada pengeluaran manual.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-hrd-layout>
