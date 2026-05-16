<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Database General</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Detail data general {{ $transaksi->tanggal->format('d M Y') }}</h2>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('transaksi-operasional.edit', $transaksi) }}" class="btn-primary btn-compact">Edit</a>
            <a href="{{ route('transaksi-operasional.index') }}" class="btn-soft btn-compact">Kembali</a>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="space-y-6">
            <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Informasi Utama</p>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Kapal</p>
                        <p class="mt-2 font-semibold text-stone-950">{{ $transaksi->kapal->nama_kapal }}</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Kendaraan</p>
                        <p class="mt-2 font-semibold text-stone-950">{{ $transaksi->kendaraan->nopol }}</p>
                        <p class="text-sm text-stone-500">{{ $transaksi->kendaraan->pemilik->nama_pemilik }}</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Rute</p>
                        <p class="mt-2 font-semibold text-stone-950">{{ $transaksi->rute }}</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Ritase dan tonase</p>
                        <p class="mt-2 font-semibold text-stone-950">{{ $transaksi->ritase }} ritase</p>
                        <p class="text-sm text-stone-500">{{ number_format((float) $transaksi->tonase, 2, ',', '.') }} ton</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-stone-500">Tanggal kegiatan</p>
                        <p class="mt-2 font-semibold text-stone-950">{{ ($transaksi->tanggal_kegiatan ?? $transaksi->tanggal)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Keterangan</p>
                <p class="mt-4 leading-7 text-stone-700">{{ $transaksi->keterangan ?: 'Belum ada keterangan tambahan.' }}</p>
            </div>
        </section>

        <section class="space-y-6">
            <div class="rounded-[2rem] bg-stone-950 p-6 text-white shadow-2xl shadow-stone-300/40">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/80">Ringkasan Keuangan</p>
                <p class="mt-4 text-3xl font-black">{{ $rupiah($transaksi->pendapatan) }}</p>
                <div class="mt-6 space-y-3 text-sm text-stone-300">
                    <div class="flex justify-between gap-3"><span>Biaya operasional</span><span>{{ $rupiah($transaksi->operasional) }}</span></div>
                    <div class="flex justify-between gap-3"><span>Sangu supir</span><span>{{ $rupiah($transaksi->sangu_supir) }}</span></div>
                    <div class="flex justify-between gap-3"><span>Terpal</span><span>{{ $rupiah($transaksi->terpal) }}</span></div>
                    <div class="flex justify-between gap-3"><span>Total lapangan</span><span>{{ $rupiah($transaksi->total_lapangan) }}</span></div>
                    <div class="flex justify-between gap-3 border-t border-white/10 pt-3 font-bold"><span>Total biaya</span><span>{{ $rupiah($transaksi->total_biaya) }}</span></div>
                    <div class="flex justify-between gap-3 font-bold {{ $transaksi->laba_kotor >= 0 ? 'text-emerald-300' : 'text-rose-300' }}"><span>Laba kotor</span><span>{{ $rupiah($transaksi->laba_kotor) }}</span></div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Gaji Telly</p>
                @if ($transaksi->gajiTelly)
                    <div class="mt-4 space-y-3 text-sm text-stone-700">
                        <div class="flex justify-between gap-3"><span>Karyawan</span><span class="font-semibold">{{ $transaksi->gajiTelly->karyawan->nama }}</span></div>
                        <div class="flex justify-between gap-3"><span>Gaji satuan</span><span>{{ $rupiah($transaksi->gajiTelly->gaji) }}</span></div>
                        <div class="flex justify-between gap-3"><span>Gaji total</span><span>{{ $rupiah($transaksi->gajiTelly->gaji_total) }}</span></div>
                        <div class="flex justify-between gap-3"><span>PPh</span><span>{{ $rupiah($transaksi->gajiTelly->pph) }}</span></div>
                        <div class="flex justify-between gap-3 border-t border-stone-100 pt-3"><span>Gaji bersih</span><span class="font-bold">{{ $rupiah($transaksi->gajiTelly->gaji_bersih) }}</span></div>
                    </div>
                @else
                    <p class="mt-4 text-sm text-stone-500">Belum ada data gaji telly.</p>
                @endif
            </div>

            <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Paguyuban</p>
                @if ($transaksi->paguyuban)
                    <div class="mt-4 space-y-3 text-sm text-stone-700">
                        <div class="flex justify-between gap-3"><span>Tanggal</span><span>{{ $transaksi->paguyuban->tanggal->format('d M Y') }}</span></div>
                        <div class="flex justify-between gap-3"><span>Tonase</span><span>{{ number_format((float) $transaksi->tonase, 2, ',', '.') }} ton</span></div>
                        <div class="flex justify-between gap-3"><span>Tarif per ton</span><span>{{ $rupiah($transaksi->paguyuban->tarif) }}</span></div>
                        <div class="flex justify-between gap-3 border-t border-stone-100 pt-3"><span>Total bayar</span><span class="font-bold">{{ $rupiah($transaksi->paguyuban->total_bayar) }}</span></div>
                    </div>
                @else
                    <p class="mt-4 text-sm text-stone-500">Belum ada data paguyuban.</p>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>
