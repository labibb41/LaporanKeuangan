<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Laporan Pengeluaran</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Form dan daftar pengeluaran admin</h2>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))
    @php($bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'])

    <section class="card space-y-6">
        @include('laporan._toolbar')

        <div class="rounded-xl border border-slate-200/50 bg-slate-50/60 p-5">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">{{ $formPengeluaran ? 'Edit Pengeluaran' : 'Input Pengeluaran' }}</p>
                    <h3 class="mt-2 text-xl font-black text-slate-900">{{ $formPengeluaran ? 'Perbarui data pengeluaran' : 'Isi data pengeluaran' }}</h3>
                </div>
                <span class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700">Total Periode: {{ $rupiah($total) }}</span>
            </div>

            <form method="POST" action="{{ $formPengeluaran ? route('pengeluaran.update', $formPengeluaran) : route('pengeluaran.store') }}" class="mt-6 space-y-5">
                @csrf
                @if ($formPengeluaran)
                    @method('PUT')
                @endif
                <input type="hidden" name="redirect_to" value="laporan">
                @include('pengeluaran._form', ['pengeluaran' => $formPengeluaran])

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">
                        {{ $formPengeluaran ? 'Simpan perubahan' : 'Simpan pengeluaran' }}
                    </button>

                    @if ($formPengeluaran)
                        <a href="{{ route('laporan.pengeluaran', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-soft">
                            Batal edit
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div>
            <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Daftar Pengeluaran</p>
                    <h3 class="mt-2 text-xl font-black text-stone-950">Tanggal, jenis, penerima, dan jumlah</h3>
                </div>
                <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">{{ $pengeluaran->total() }} data</span>
            </div>

            <div class="overflow-hidden rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">Jenis</th>
                            <th class="px-4 py-3 font-semibold">Nama Pengeluaran</th>
                            <th class="px-4 py-3 font-semibold">Jumlah</th>
                            <th class="px-4 py-3 font-semibold">Penerima</th>
                            <th class="px-4 py-3 font-semibold">Keterangan</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($pengeluaran as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->tanggal->format('d M Y') }}</td>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->jenis }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->nama_kegiatan ?: '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-rose-700">{{ $rupiah($item->jumlah) }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->penerima ?: '-' }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->keterangan ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('laporan.pengeluaran', ['bulan' => $bulan, 'tahun' => $tahun, 'edit' => $item->id]) }}" class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800 hover:bg-sky-200">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('pengeluaran.destroy', $item) }}" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-stone-500">Belum ada pengeluaran yang dicatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($pengeluaran->isNotEmpty())
                        <tfoot class="bg-stone-50 border-t border-stone-200">
                            <tr>
                                <th class="px-4 py-4 text-left font-bold uppercase tracking-widest text-stone-600" colspan="3">TOTAL KESELURUHAN BULAN {{ strtoupper($bulanList[$bulan - 1]) }} TAHUN {{ $tahun }}</th>
                                <th class="px-4 py-4 text-left font-black text-rose-700 text-base" colspan="4">{{ $rupiah($total) }}</th>
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
        </div>
    </section>
</x-app-layout>
