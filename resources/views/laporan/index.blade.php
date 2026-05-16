<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Pusat laporan bulanan</h2>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.75rem] bg-gradient-to-br from-sky-600 to-indigo-600 p-5 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/80">Pendapatan</p>
                <p class="mt-3 text-2xl font-black">{{ $rupiah($totalPendapatan) }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Biaya Operasional</p>
                <p class="mt-3 text-2xl font-black text-stone-950">{{ $rupiah($totalBiayaOperasional) }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Total Pengeluaran</p>
                <p class="mt-3 text-2xl font-black text-stone-950">{{ $rupiah($totalPengeluaran) }}</p>
            </div>
            <div class="rounded-[1.75rem] border {{ $labaBersih >= 0 ? 'border-emerald-200 bg-emerald-50' : 'border-rose-200 bg-rose-50' }} p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] {{ $labaBersih >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">Laba Bersih</p>
                <p class="mt-3 text-2xl font-black {{ $labaBersih >= 0 ? 'text-emerald-900' : 'text-rose-900' }}">{{ $rupiah($labaBersih) }}</p>
                <p class="mt-2 text-sm {{ $labaBersih >= 0 ? 'text-emerald-800' : 'text-rose-800' }}">{{ $jumlahTransaksi }} transaksi</p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
            @foreach ($daftarLaporan as $item)
                <a href="{{ $item['route'] }}" class="rounded-[1.75rem] border border-stone-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <p class="text-lg font-black text-stone-950">{{ $item['title'] }}</p>
                    <p class="mt-3 text-sm leading-6 text-stone-600">{{ $item['description'] }}</p>
                    <p class="mt-5 text-sm font-semibold text-sky-700">Buka laporan</p>
                </a>
            @endforeach
        </div>
    </section>
</x-app-layout>
