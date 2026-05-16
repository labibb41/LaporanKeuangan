<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Paguyuban</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Biaya paguyuban berdasarkan tonase</h2>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.75rem] bg-gradient-to-br from-sky-600 to-indigo-600 p-5 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/80">Tarif</p>
                <p class="mt-3 text-2xl font-black">{{ $rupiah($tarifPaguyuban) }}</p>
                <p class="mt-2 text-sm text-white/80">Per ton</p>
            </div>
            <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Total Tonase</p>
                <p class="mt-3 text-2xl font-black text-stone-950">{{ number_format((float) $laporan->sum('tonase'), 2, ',', '.') }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-sky-100 bg-sky-50/50 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-700">Total Paguyuban</p>
                <p class="mt-3 text-2xl font-black text-sky-900">{{ $rupiah($laporan->sum(fn ($item) => (float) ($item->paguyuban?->total_bayar ?? 0))) }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
            <table class="min-w-full divide-y divide-stone-100 text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Kapal</th>
                        <th class="px-4 py-3 font-semibold">Kendaraan</th>
                        <th class="px-4 py-3 font-semibold">Rute</th>
                        <th class="px-4 py-3 font-semibold">Tonase</th>
                        <th class="px-4 py-3 font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 bg-white">
                    @forelse ($laporan as $item)
                        <tr>
                            <td class="px-4 py-3">{{ $item->tanggal->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->kapal->nama_kapal }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->kendaraan->nopol }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->rute }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->paguyuban?->total_bayar ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-stone-500">Belum ada biaya paguyuban pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
