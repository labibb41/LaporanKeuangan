<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Partner</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Rekap per pemilik kendaraan</h2>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
            <table class="min-w-full divide-y divide-stone-100 text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Pemilik</th>
                        <th class="px-4 py-3 font-semibold">Kendaraan</th>
                        <th class="px-4 py-3 font-semibold">Transaksi</th>
                        <th class="px-4 py-3 font-semibold">Pendapatan</th>
                        <th class="px-4 py-3 font-semibold">Biaya</th>
                        <th class="px-4 py-3 font-semibold">Pendapatan Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 bg-white">
                    @forelse ($laporan as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->nama_pemilik }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->total_kendaraan }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->total_transaksi }}</td>
                            <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->total_pendapatan) }}</td>
                            <td class="px-4 py-3 font-semibold text-stone-700">{{ $rupiah($item->total_biaya) }}</td>
                            <td class="px-4 py-3 font-semibold {{ $item->laba_bersih >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $rupiah($item->laba_bersih) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-stone-500">Belum ada data partner pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
