<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Operasional</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Rekap operasional per kapal</h2>
            <p class="mt-2 text-sm text-stone-600">Data di bawah mengambil rangkuman dari Database General, lalu bisa dilengkapi manual per kapal.</p>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <section class="card space-y-6">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <a href="{{ route('operasional.create', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-primary btn-compact">
                Tambah data operasional
            </a>

            <div class="min-w-[10rem] flex-1 md:flex-none">
                <label for="bulan" class="mb-2 block text-sm font-semibold text-stone-700">Filter bulan</label>
                <input id="bulan" name="bulan" type="number" min="1" max="12" value="{{ $bulan }}" class="field py-2">
            </div>
            <div class="min-w-[10rem] flex-1 md:flex-none">
                <label for="tahun" class="mb-2 block text-sm font-semibold text-stone-700">Filter tahun</label>
                <input id="tahun" name="tahun" type="number" min="2020" value="{{ $tahun }}" class="field py-2">
            </div>
            <button type="submit" class="btn-primary btn-compact">
                Terapkan
            </button>
        </form>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.75rem] bg-gradient-to-br from-sky-600 to-indigo-600 p-5 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/80">Kapal Dirangkum</p>
                <p class="mt-3 text-2xl font-black">{{ $barisOperasional->count() }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Baris General</p>
                <p class="mt-3 text-2xl font-black text-stone-950">{{ $jumlahGeneral }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-sky-100 bg-sky-50/50 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-700">Total Operasional</p>
                <p class="mt-3 text-2xl font-black text-sky-900">{{ $rupiah($barisOperasional->sum('total')) }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-[1.5rem] border border-stone-100">
            <table class="min-w-full divide-y divide-stone-100 text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Kapal</th>
                        <th class="px-4 py-3 font-semibold">Rute</th>
                        <th class="px-4 py-3 font-semibold">Trips</th>
                        <th class="px-4 py-3 font-semibold">Tonnase</th>
                        <th class="px-4 py-3 font-semibold">Sangu Supir</th>
                        <th class="px-4 py-3 font-semibold">Terpal</th>
                        <th class="px-4 py-3 font-semibold">Operasional</th>
                        <th class="px-4 py-3 font-semibold">Total</th>
                        <th class="px-4 py-3 font-semibold">Telly</th>
                        <th class="px-4 py-3 font-semibold">Tgl Kegiatan</th>
                        <th class="px-4 py-3 font-semibold">Keterangan</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 bg-white">
                    @forelse ($barisOperasional as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-stone-900">{{ $item->kapal_nama }}</p>
                                <p class="text-xs text-stone-500">{{ $item->general_count }} baris dari general</p>
                            </td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->rute }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->trips }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $rupiah($item->sangu_supir) }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $rupiah($item->terpal) }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $rupiah($item->operasional) }}</td>
                            <td class="px-4 py-3 font-semibold text-stone-900">{{ $rupiah($item->total) }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->telly ?: '-' }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->tanggal_kegiatan?->format('d/m/Y') ?: '-' }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $item->keterangan ?: '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @if ($item->manual_id)
                                        <a href="{{ route('operasional.edit', $item->manual_id) }}" class="rounded-full bg-stone-100 px-3 py-2 text-xs font-semibold text-stone-700">Edit</a>
                                        <form method="POST" action="{{ route('operasional.destroy', $item->manual_id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700" onclick="return confirm('Hapus data operasional ini?')">Hapus</button>
                                        </form>
                                    @else
                                        <a href="{{ route('operasional.create', ['bulan' => $bulan, 'tahun' => $tahun, 'kapal_id' => $item->kapal_id]) }}" class="rounded-full bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 hover:bg-sky-100">Lengkapi</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-6 text-center text-stone-500">Belum ada data operasional pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
