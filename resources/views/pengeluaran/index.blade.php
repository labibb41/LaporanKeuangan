<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Keuangan</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Data pengeluaran perusahaan</h2>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('pengeluaran.create') }}" class="btn-primary">
                Tambah pengeluaran
            </a>
        </div>
    </x-slot>

    @php
        $rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    @endphp
    @php($bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'])

    <div class="space-y-6">
        <section class="card">
            <form method="GET" class="grid gap-4 md:grid-cols-[1fr_1fr_auto]">
                <div>
                    <label for="bulan" class="mb-2 block text-sm font-semibold text-stone-700">Filter bulan</label>
                    <select id="bulan" name="bulan" class="field py-2">
                        @foreach($bulanList as $idx => $namaBulan)
                            <option value="{{ $idx + 1 }}" @selected($bulan == $idx + 1)>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tahun" class="mb-2 block text-sm font-semibold text-stone-700">Filter tahun</label>
                    <input id="tahun" name="tahun" type="number" min="2020" value="{{ $tahun }}" class="field py-2">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary btn-compact">
                        Terapkan
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Total Pengeluaran</p>
                    <h3 class="mt-2 text-2xl font-black text-stone-950">{{ $rupiah($total) }}</h3>
                </div>
                <span class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700">{{ $pengeluaran->total() }} data</span>
            </div>

            <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">Jenis</th>
                            <th class="px-4 py-3 font-semibold">Penerima</th>
                            <th class="px-4 py-3 font-semibold">Jumlah</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($pengeluaran as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->tanggal->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-stone-900">{{ $item->jenis }}</p>
                                    <p class="text-xs text-stone-500">{{ $item->nama_kegiatan ?: 'Tanpa kegiatan' }}</p>
                                </td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->penerima ?: '-' }}</td>
                                <td class="px-4 py-3 font-bold text-rose-700">{{ $rupiah($item->jumlah) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('pengeluaran.edit', $item) }}" class="rounded-full bg-stone-100 px-3 py-2 text-xs font-semibold text-stone-700">Edit</a>
                                        <form method="POST" action="{{ route('pengeluaran.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700" onclick="return confirm('Hapus pengeluaran ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-stone-500">Belum ada pengeluaran yang dicatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($pengeluaran->isNotEmpty())
                        <tfoot class="bg-stone-50 border-t border-stone-200">
                            <tr>
                                <th class="px-4 py-4 text-left font-bold uppercase tracking-widest text-stone-600" colspan="3">TOTAL KESELURUHAN BULAN {{ strtoupper($bulanList[$bulan - 1]) }} TAHUN {{ $tahun }}</th>
                                <th class="px-4 py-4 text-left font-black text-rose-700 text-base" colspan="2">{{ $rupiah($total) }}</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    {{ $pengeluaran->links() }}
                </div>
                <a href="{{ route('laporan.pengeluaran.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="btn-primary">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Laporan PDF
                </a>
            </div>
        </section>
    </div>
</x-app-layout>
