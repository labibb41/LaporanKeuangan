<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-700">Detail Pemilik</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">{{ $pemilik->nama_pemilik }}</h2>
            </div>
            <a href="{{ route('pemilik.index') }}" class="btn-soft">Kembali</a>
        </div>
    </x-slot>

    <section class="card">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl font-black text-stone-950">Daftar Kendaraan</h3>
                <p class="text-sm text-stone-500">Kendaraan yang dimiliki oleh {{ $pemilik->nama_pemilik }}</p>
            </div>
            <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">{{ $pemilik->kendaraan->count() }} unit</span>
        </div>

        <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
            <table class="min-w-full divide-y divide-stone-100 text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nopol</th>
                        <th class="px-4 py-3 font-semibold text-center">Total Transaksi</th>
                        <th class="px-4 py-3 font-semibold text-right">Ditambahkan Pada</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 bg-white">
                    @forelse ($pemilik->kendaraan as $item)
                        <tr>
                            <td class="px-4 py-3 font-bold text-stone-900">{{ $item->nopol }}</td>
                            <td class="px-4 py-3 text-center text-stone-600">{{ $item->transaksi_operasional_count }} transaksi</td>
                            <td class="px-4 py-3 text-right text-stone-500">{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-stone-500">Belum ada kendaraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
