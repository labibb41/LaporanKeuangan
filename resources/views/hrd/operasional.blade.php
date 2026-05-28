<x-hrd-layout>
    <x-slot name="header">
        <p class="page-label">Rekap HRD</p>
        <h2 class="page-title" style="font-family:'Montserrat',sans-serif;">Operasional per Kapal</h2>
        <p class="page-sub">Ringkasan ritase, tonase, dan pendapatan — <strong>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</strong></p>
    </x-slot>

    @php
        $rupiah = fn($n) => 'Rp ' . number_format((float)$n, 0, ',', '.');
        $months = range(1,12);
    @endphp

    {{-- Filter --}}
    <form method="GET" action="{{ route('hrd.operasional') }}" class="mb-5 card flex flex-wrap items-end gap-3">
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

    {{-- Summary Cards --}}
    <div class="mb-5 grid gap-4 sm:grid-cols-3">
        <div class="stat-card">
            <p class="section-label">Total Tonase</p>
            <p class="mt-2 text-2xl font-black text-slate-900" style="font-family:'Montserrat',sans-serif;">{{ number_format($totalTonase, 1, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-0.5">ton</p>
        </div>
        <div class="stat-card">
            <p class="section-label">Total Ritase</p>
            <p class="mt-2 text-2xl font-black text-slate-900" style="font-family:'Montserrat',sans-serif;">{{ number_format($totalRitase, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-0.5">trip</p>
        </div>
        <div class="rounded-xl p-4 shadow-sm" style="background: linear-gradient(135deg, #164A41, #0f3830);">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/70">Total Pendapatan</p>
            <p class="mt-2 text-2xl font-black text-white" style="font-family:'Montserrat',sans-serif;">{{ $rupiah($totalPendapatan) }}</p>
            <p class="text-xs text-white/50 mt-0.5">bulan ini</p>
        </div>
    </div>

    {{-- Per Kapal Table --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4 gap-4">
            <div>
                <p class="section-label">Detail</p>
                <h3 class="section-title" style="font-family:'Montserrat',sans-serif;">Rincian per Kapal</h3>
            </div>
            <span class="badge-forest">{{ $perKapal->count() }} kapal aktif</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Kapal</th>
                        <th class="text-center">Transaksi</th>
                        <th class="text-right">Total Ritase</th>
                        <th class="text-right">Total Tonase</th>
                        <th class="text-right">Pendapatan</th>
                        <th>Porsi Tonase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perKapal as $row)
                        @php $pct = $totalTonase > 0 ? round((float)$row->total_tonase / $totalTonase * 100, 1) : 0; @endphp
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $row->nama_kapal }}</td>
                            <td class="text-center">{{ $row->total_transaksi }}</td>
                            <td class="text-right">{{ number_format((int)$row->total_ritase, 0, ',', '.') }}</td>
                            <td class="text-right font-semibold">{{ number_format((float)$row->total_tonase, 1, ',', '.') }}</td>
                            <td class="text-right font-bold" style="color:#164A41;">{{ $rupiah($row->total_pendapatan) }}</td>
                            <td class="min-w-[120px]">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background: linear-gradient(90deg, #164A41, #4D774E);"></div>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-500 w-10 text-right">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state text-slate-400">Belum ada data operasional pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($perKapal->isNotEmpty())
                <tfoot>
                    <tr style="background: #f0faf4;">
                        <td class="px-3 py-2.5 text-xs font-black text-slate-600">TOTAL</td>
                        <td class="px-3 py-2.5 text-center text-xs font-black text-slate-700">{{ $perKapal->sum('total_transaksi') }}</td>
                        <td class="px-3 py-2.5 text-right text-xs font-black text-slate-700">{{ number_format((int)$perKapal->sum('total_ritase'), 0, ',', '.') }}</td>
                        <td class="px-3 py-2.5 text-right text-xs font-black text-slate-700">{{ number_format($totalTonase, 1, ',', '.') }}</td>
                        <td class="px-3 py-2.5 text-right text-xs font-black" style="color:#164A41;">{{ $rupiah($totalPendapatan) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-hrd-layout>
