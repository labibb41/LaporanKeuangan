<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Gaji Telly</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Rekap gaji telly per karyawan</h2>
        </div>
    </x-slot>

    @php
        $rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    @endphp

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="flex flex-wrap items-end gap-3 border-b border-stone-200/70 pb-5">
            <form method="GET" action="{{ route('laporan.telly') }}" class="flex flex-wrap items-end gap-3 w-full">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="min-w-[16rem] flex-1">
                    <label for="karyawan_id" class="mb-2 block text-sm font-semibold text-stone-700">Pilih karyawan</label>
                    <select id="karyawan_id" name="karyawan_id" class="field-white">
                        <option value="">Semua karyawan (rekap)</option>
                        @foreach ($daftarKaryawan as $karyawan)
                            <option value="{{ $karyawan->id }}" @selected((int) $karyawanId === (int) $karyawan->id)>{{ $karyawan->nama }}{{ $karyawan->jabatan ? ' - '.$karyawan->jabatan : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary btn-compact">Tampilkan</button>

                @if ($karyawanId)
                    <a href="{{ route('laporan.telly', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-soft btn-compact">Reset</a>
                @endif

                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-stone-500">Detail dihitung per bulan/tahun.</p>
            </form>
        </div>

        @if ($karyawanId)
            <div>
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Karyawan</p>
                        <h3 class="mt-2 text-xl font-black text-stone-950">{{ $selectedKaryawan?->nama ?? 'Tidak ditemukan' }}</h3>
                        <p class="mt-1 text-sm text-stone-600">{{ $selectedKaryawan?->jabatan ?: 'Telly' }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-sky-100 bg-sky-50/50 px-5 py-4 text-right">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Periode</p>
                        <p class="mt-1 text-sm font-bold text-stone-900">{{ str_pad((string) $bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}</p>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-stone-100">
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Kegiatan</th>
                                <th class="px-4 py-3 font-semibold">Rute</th>
                                <th class="px-4 py-3 font-semibold">Ritase</th>
                                <th class="px-4 py-3 font-semibold">Tonase</th>
                                <th class="px-4 py-3 font-semibold">Gaji (Rp/ton)</th>
                                <th class="px-4 py-3 font-semibold">Gaji Total</th>
                                <th class="px-4 py-3 font-semibold">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 bg-white">
                            @forelse ($detail as $item)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-stone-900">
                                        {{ $item->nama_kapal }}
                                        <p class="mt-1 text-xs font-medium text-stone-500">{{ \Illuminate\Support\Carbon::parse($item->tanggal)->format('d/m/Y') }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-stone-600">{{ $item->rute }}</td>
                                    <td class="px-4 py-3 text-stone-600">{{ (int) $item->ritase }}</td>
                                    <td class="px-4 py-3 text-stone-600">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-stone-600">{{ $rupiah($item->gaji) }}</td>
                                    <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->gaji_total) }}</td>
                                    <td class="px-4 py-3 text-stone-600">{{ $item->keterangan ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-stone-500">Belum ada detail gaji telly pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($detail->isNotEmpty())
                            <tfoot class="bg-stone-50 text-stone-700">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold" colspan="2">TOTAL</th>
                                    <th class="px-4 py-3 text-left font-semibold">{{ (int) $detail->sum('ritase') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold">{{ number_format((float) $detail->sum('tonase'), 2, ',', '.') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold">-</th>
                                    <th class="px-4 py-3 text-left font-semibold">{{ $rupiah($detail->sum('gaji_total')) }}</th>
                                    <th class="px-4 py-3 text-left font-semibold">-</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                @if ($detail->isNotEmpty())
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Pajak PPh</p>
                            <p class="mt-3 text-2xl font-black text-stone-950">{{ $rupiah($detail->sum('pph')) }}</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Gaji Kotor</p>
                            <p class="mt-3 text-2xl font-black text-stone-950">{{ $rupiah($detail->sum('gaji_total')) }}</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-700">Net Gaji Bersih</p>
                            <p class="mt-3 text-2xl font-black text-emerald-900">{{ $rupiah($detail->sum('gaji_bersih')) }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Karyawan</th>
                            <th class="px-4 py-3 font-semibold">Jabatan</th>
                            <th class="px-4 py-3 font-semibold">Aktivitas</th>
                            <th class="px-4 py-3 font-semibold">Gaji Kotor</th>
                            <th class="px-4 py-3 font-semibold">PPh</th>
                            <th class="px-4 py-3 font-semibold">Gaji Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($laporan as $item)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->nama }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->jabatan ?: '-' }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->total_aktivitas }}</td>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->gaji_kotor) }}</td>
                                <td class="px-4 py-3 font-semibold text-stone-700">{{ $rupiah($item->total_pph) }}</td>
                                <td class="px-4 py-3 font-semibold text-emerald-700">{{ $rupiah($item->gaji_bersih) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-stone-500">Belum ada data gaji telly pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-app-layout>
