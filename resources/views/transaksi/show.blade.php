<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Database General</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Detail data general {{ $transaksi->tanggal->format('d M Y') }}</h2>
        </div>

    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <div class="card max-w-5xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-x-16 gap-y-12">
            
            {{-- Kolom Kiri --}}
            <div class="space-y-12">
                {{-- Informasi Utama --}}
                <div>
                    <div class="mb-6 border-b border-stone-200/70 pb-4">
                        <p class="text-lg font-bold text-stone-900">Informasi Utama</p>
                    </div>
                    <table class="w-full text-sm text-left">
                        <tbody>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Tanggal kegiatan</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ ($transaksi->tanggal_kegiatan ?? $transaksi->tanggal)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Kapal</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $transaksi->kapal->nama_kapal }}</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Kendaraan</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 align-top">
                                    <span class="font-semibold text-stone-950 block">{{ $transaksi->kendaraan->nopol }}</span>
                                    <span class="text-xs text-stone-500">({{ $transaksi->kendaraan->pemilik->nama_pemilik }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Rute</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $transaksi->rute }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Muatan dan Biaya Dasar --}}
                <div>
                    <div class="mb-6 border-b border-stone-200/70 pb-4">
                        <p class="text-lg font-bold text-stone-900">Muatan dan Biaya Dasar</p>
                    </div>
                    <table class="w-full text-sm text-left">
                        <tbody>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Ritase & Tonase</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $transaksi->ritase }} ritase &middot; {{ number_format((float) $transaksi->tonase, 2, ',', '.') }} ton</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Biaya operasional</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $rupiah($transaksi->operasional) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Sangu supir</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $rupiah($transaksi->sangu_supir) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Terpal</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $rupiah($transaksi->terpal) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Total lapangan</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-bold text-stone-950 align-top">{{ $rupiah($transaksi->total_lapangan) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                {{-- Keterangan Tambahan --}}
                <div>
                    <div class="mb-6 border-b border-stone-200/70 pb-4">
                        <p class="text-lg font-bold text-stone-900">Keterangan Tambahan</p>
                    </div>
                    <p class="text-sm leading-6 text-stone-700">{{ $transaksi->keterangan ?: 'Tidak ada keterangan tambahan.' }}</p>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-12">
                {{-- Ringkasan Keuangan --}}
                <div>
                    <div class="mb-6 border-b border-stone-200/70 pb-4">
                        <p class="text-lg font-bold text-stone-900">Ringkasan Keuangan</p>
                    </div>
                    <table class="w-full text-sm text-left">
                        <tbody>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Pendapatan kotor</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-bold text-emerald-600 align-top">{{ $rupiah($transaksi->pendapatan) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Total biaya</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-semibold text-rose-600 align-top">{{ $rupiah($transaksi->total_biaya) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Laba bersih</td>
                                <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                <td class="py-2.5 pl-2 font-black {{ $transaksi->laba_kotor >= 0 ? 'text-emerald-700' : 'text-rose-700' }} text-lg align-top">{{ $rupiah($transaksi->laba_kotor) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Gaji Telly --}}
                <div>
                    <div class="mb-6 border-b border-stone-200/70 pb-4">
                        <p class="text-lg font-bold text-stone-900">Gaji Telly</p>
                    </div>
                    @if ($transaksi->gajiTelly)
                        <table class="w-full text-sm text-left">
                            <tbody>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Karyawan</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $transaksi->gajiTelly->karyawan->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Gaji satuan</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $rupiah($transaksi->gajiTelly->gaji) }} / ton</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Total kotor</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $rupiah($transaksi->gajiTelly->gaji_total) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Potongan PPh</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-semibold text-rose-600 align-top">{{ $rupiah($transaksi->gajiTelly->pph) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Gaji bersih</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-bold text-stone-950 align-top">{{ $rupiah($transaksi->gajiTelly->gaji_bersih) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-stone-500 italic">Belum ada data gaji telly.</p>
                    @endif
                </div>

                {{-- Paguyuban --}}
                <div>
                    <div class="mb-6 border-b border-stone-200/70 pb-4">
                        <p class="text-lg font-bold text-stone-900">Paguyuban</p>
                    </div>
                    @if ($transaksi->paguyuban)
                        <table class="w-full text-sm text-left">
                            <tbody>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Tanggal bayar</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $transaksi->paguyuban->tanggal->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Tonase</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ number_format((float) $transaksi->tonase, 2, ',', '.') }} ton</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Tarif per ton</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-semibold text-stone-950 align-top">{{ $rupiah($transaksi->paguyuban->tarif) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 pr-3 font-semibold text-stone-700 w-[140px] align-top">Total bayar</td>
                                    <td class="py-2.5 px-2 font-semibold text-stone-700 w-[1%] align-top">:</td>
                                    <td class="py-2.5 pl-2 font-bold text-stone-950 align-top">{{ $rupiah($transaksi->paguyuban->total_bayar) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-stone-500 italic">Belum ada data paguyuban.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-stone-200 flex items-center justify-end gap-3">
            <a href="{{ route('transaksi-operasional.index') }}" class="btn-soft">Kembali</a>
            <a href="{{ route('transaksi-operasional.edit', $transaksi) }}" class="btn-primary">Edit Data</a>
        </div>
    </div>
</x-app-layout>
