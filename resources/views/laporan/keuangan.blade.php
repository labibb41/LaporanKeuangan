<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Keuangan</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Laba rugi bulanan</h2>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.75rem] bg-gradient-to-br from-sky-600 to-indigo-600 p-5 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/80">Total Pendapatan</p>
                <p class="mt-3 text-2xl font-black">{{ $rupiah($pendapatan) }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Total Biaya</p>
                <p class="mt-3 text-2xl font-black text-stone-950">{{ $rupiah($totalBiaya) }}</p>
            </div>
            <div class="rounded-[1.75rem] border {{ $labaBersih >= 0 ? 'border-emerald-200 bg-emerald-50' : 'border-rose-200 bg-rose-50' }} p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] {{ $labaBersih >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">Laba Bersih</p>
                <p class="mt-3 text-2xl font-black {{ $labaBersih >= 0 ? 'text-emerald-900' : 'text-rose-900' }}">{{ $rupiah($labaBersih) }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="card">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Komponen Biaya</p>
                <div class="mt-5 space-y-3 text-sm">
                    <div class="flex justify-between gap-3"><span>Sangu supir</span><span class="font-semibold">{{ $rupiah($biayaSupir) }}</span></div>
                    <div class="flex justify-between gap-3"><span>Terpal</span><span class="font-semibold">{{ $rupiah($biayaTerpal) }}</span></div>
                    <div class="flex justify-between gap-3"><span>Biaya operasional</span><span class="font-semibold">{{ $rupiah($biayaOperasional) }}</span></div>
                    <div class="flex justify-between gap-3"><span>Gaji telly</span><span class="font-semibold">{{ $rupiah($biayaTelly) }}</span></div>
                    <div class="flex justify-between gap-3"><span>Paguyuban</span><span class="font-semibold">{{ $rupiah($biayaPaguyuban) }}</span></div>
                    <div class="flex justify-between gap-3 text-rose-700"><span>Pengeluaran lain</span><span class="font-semibold">{{ $rupiah($pengeluaranLain) }}</span></div>
                    <div class="flex justify-between gap-3 border-t border-stone-100 pt-3 text-base font-black"><span>Total biaya</span><span>{{ $rupiah($totalBiaya) }}</span></div>
                </div>
            </div>

            <div class="card">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Transaksi Operasional</p>
                <div class="mt-5 overflow-hidden rounded-[1.5rem] border border-stone-100">
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Tanggal</th>
                                <th class="px-4 py-3 font-semibold">Pendapatan</th>
                                <th class="px-4 py-3 font-semibold">Biaya</th>
                                <th class="px-4 py-3 font-semibold">Laba</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 bg-white">
                            @forelse ($transaksi as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $item->tanggal->format('d M Y') }}</td>
                                    <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->pendapatan) }}</td>
                                    <td class="px-4 py-3 font-semibold text-stone-700">{{ $rupiah($item->total_biaya) }}</td>
                                    <td class="px-4 py-3 font-semibold {{ $item->laba_kotor >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $rupiah($item->laba_kotor) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-stone-500">Belum ada transaksi operasional pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Pengeluaran Manual</p>
                    <h3 class="mt-2 text-xl font-black text-stone-950">ATK, gaji, dan biaya lain</h3>
                </div>
                <span class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700">{{ $pengeluaran->count() }} data</span>
            </div>

            <div class="mt-5 overflow-hidden rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">Jenis</th>
                            <th class="px-4 py-3 font-semibold">Penerima</th>
                            <th class="px-4 py-3 font-semibold">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($pengeluaran as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->tanggal->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-stone-900">{{ $item->jenis }}</p>
                                    <p class="text-xs text-stone-500">{{ $item->nama_kegiatan ?: '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->penerima ?: '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-rose-700">{{ $rupiah($item->jumlah) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-stone-500">Belum ada pengeluaran manual pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
        </div>
    </section>
</x-app-layout>
