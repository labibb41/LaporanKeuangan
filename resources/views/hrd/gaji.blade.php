<x-hrd-layout>
    <x-slot name="header">
        <p class="page-label">Rekap HRD</p>
        <h2 class="page-title" style="font-family:'Montserrat',sans-serif;">Rekap Gaji Telly</h2>
        <p class="page-sub">Data gaji per karyawan — <strong>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</strong></p>
    </x-slot>

    @php
        $rupiah = fn($n) => 'Rp ' . number_format((float)$n, 0, ',', '.');
        $months = range(1,12);
    @endphp

    {{-- Filter --}}
    <form method="GET" action="{{ route('hrd.gaji') }}" class="mb-5 card flex flex-wrap items-end gap-3">
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
        <div>
            <label class="label">Filter Karyawan</label>
            <select name="karyawan_id" class="field w-48">
                <option value="">— Semua Karyawan —</option>
                @foreach ($daftarKaryawan as $k)
                    <option value="{{ $k->id }}" @selected($k->id == $karyawanId)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary btn-compact">Terapkan</button>
        @if($karyawanId)
            <a href="{{ route('hrd.gaji', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-soft btn-compact">Reset</a>
        @endif
    </form>

    @if ($karyawanId && $selectedKaryawan)
        {{-- ── Detail per Karyawan ── --}}
        <div class="card">
            <div class="mb-4 flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-black text-white"
                          style="background: linear-gradient(135deg, #164A41, #4D774E); width:40px;height:40px;min-width:40px;">
                        {{ strtoupper(str($selectedKaryawan->nama)->take(1)) }}
                    </span>
                    <div>
                        <p class="font-black text-slate-900" style="font-family:'Montserrat',sans-serif;">{{ $selectedKaryawan->nama }}</p>
                        <p class="text-xs text-slate-500">{{ $selectedKaryawan->jabatan ?: 'Karyawan' }}</p>
                    </div>
                </div>
            </div>

            @if ($detail->isNotEmpty())
            {{-- Ringkasan --}}
            <div class="mb-4 grid gap-3 sm:grid-cols-3">
                <div class="rounded-lg p-3 text-center" style="background: #f0faf4;">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Gaji Kotor</p>
                    <p class="mt-1 text-base font-black" style="color:#164A41;">{{ $rupiah($detail->sum('gaji_total')) }}</p>
                </div>
                <div class="rounded-lg p-3 text-center" style="background: #fff8ed;">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Total PPh</p>
                    <p class="mt-1 text-base font-black text-amber-700">{{ $rupiah($detail->sum('pph')) }}</p>
                </div>
                <div class="rounded-lg p-3 text-center" style="background: #f0faf4; border: 1.5px solid #9DC88D;">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Gaji Bersih</p>
                    <p class="mt-1 text-base font-black" style="color:#164A41;">{{ $rupiah($detail->sum('gaji_bersih')) }}</p>
                </div>
            </div>
            @endif

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kapal</th>
                            <th>Rute</th>
                            <th>Ritase</th>
                            <th>Tonase</th>
                            <th>Gaji Kotor</th>
                            <th>PPh</th>
                            <th>Gaji Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detail as $row)
                            <tr>
                                <td class="whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                                <td class="font-semibold text-slate-800">{{ $row->nama_kapal }}</td>
                                <td class="text-slate-600">{{ $row->rute }}</td>
                                <td class="text-center">{{ $row->ritase }}</td>
                                <td class="text-right">{{ number_format((float)$row->tonase, 1, ',', '.') }}</td>
                                <td class="text-right font-semibold text-slate-700">{{ $rupiah($row->gaji_total) }}</td>
                                <td class="text-right text-amber-700">{{ $rupiah($row->pph) }}</td>
                                <td class="text-right font-bold" style="color:#164A41;">{{ $rupiah($row->gaji_bersih) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state text-slate-400">Tidak ada data gaji untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($detail->isNotEmpty())
                    <tfoot>
                        <tr style="background: #f0faf4;">
                            <td colspan="5" class="px-3 py-2.5 text-xs font-black text-slate-600">TOTAL</td>
                            <td class="px-3 py-2.5 text-right text-xs font-black text-slate-700">{{ $rupiah($detail->sum('gaji_total')) }}</td>
                            <td class="px-3 py-2.5 text-right text-xs font-black text-amber-700">{{ $rupiah($detail->sum('pph')) }}</td>
                            <td class="px-3 py-2.5 text-right text-xs font-black" style="color:#164A41;">{{ $rupiah($detail->sum('gaji_bersih')) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

    @else
        {{-- ── Ringkasan Semua Karyawan ── --}}
        <div class="card">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <p class="section-label">Rekap Bulanan</p>
                    <h3 class="section-title" style="font-family:'Montserrat',sans-serif;">Semua Karyawan</h3>
                </div>
                @if ($ringkasan->isNotEmpty())
                    <div class="text-right text-xs">
                        <p class="text-slate-500">Total Gaji Bersih</p>
                        <p class="text-base font-black" style="color:#164A41;">{{ $rupiah($ringkasan->sum('gaji_bersih')) }}</p>
                    </div>
                @endif
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th class="text-center">Aktivitas</th>
                            <th class="text-right">Gaji Kotor</th>
                            <th class="text-right">Total PPh</th>
                            <th class="text-right">Gaji Bersih</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ringkasan as $row)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $row->nama }}</td>
                                <td class="text-slate-500">{{ $row->jabatan ?: '—' }}</td>
                                <td class="text-center">{{ $row->total_aktivitas }}</td>
                                <td class="text-right text-slate-700">{{ $rupiah($row->gaji_kotor) }}</td>
                                <td class="text-right text-amber-700">{{ $rupiah($row->total_pph) }}</td>
                                <td class="text-right font-bold" style="color:#164A41;">{{ $rupiah($row->gaji_bersih) }}</td>
                                <td>
                                    <a href="{{ route('hrd.gaji', ['bulan' => $bulan, 'tahun' => $tahun, 'karyawan_id' => $row->karyawan_id]) }}"
                                       class="badge-forest">Detail →</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state text-slate-400">Belum ada data gaji pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($ringkasan->isNotEmpty())
                    <tfoot>
                        <tr style="background: #f0faf4;">
                            <td colspan="3" class="px-3 py-2.5 text-xs font-black text-slate-600">TOTAL</td>
                            <td class="px-3 py-2.5 text-right text-xs font-black text-slate-700">{{ $rupiah($ringkasan->sum('gaji_kotor')) }}</td>
                            <td class="px-3 py-2.5 text-right text-xs font-black text-amber-700">{{ $rupiah($ringkasan->sum('total_pph')) }}</td>
                            <td class="px-3 py-2.5 text-right text-xs font-black" style="color:#164A41;">{{ $rupiah($ringkasan->sum('gaji_bersih')) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    @endif
</x-hrd-layout>
