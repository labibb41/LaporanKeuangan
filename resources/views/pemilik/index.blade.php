<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Master Data</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Data pemilik kendaraan</h2>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[0.75fr_1.25fr]">
        <section class="card">
            <h3 class="text-xl font-black text-stone-950">Tambah pemilik</h3>
            <form method="POST" action="{{ route('pemilik.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="nama_pemilik" class="label">Nama pemilik</label>
                    <input id="nama_pemilik" name="nama_pemilik" type="text" value="{{ old('nama_pemilik') }}" class="field" required>
                    @error('nama_pemilik')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    Simpan pemilik
                </button>
            </form>
        </section>

        <section class="card">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Daftar Pemilik</p>
                    <h3 class="mt-2 text-xl font-black text-stone-950">Master pemilik aktif</h3>
                </div>
                <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">{{ $pemilik->total() }} data</span>
            </div>

            <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-stone-100">
                <table class="min-w-full divide-y divide-stone-100 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Nama</th>
                            <th class="px-4 py-3 font-semibold">Kendaraan</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 bg-white">
                        @forelse ($pemilik as $item)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-stone-900">{{ $item->nama_pemilik }}</td>
                                <td class="px-4 py-3 text-stone-600">{{ $item->kendaraan_count }} unit</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('pemilik.edit', $item) }}" class="rounded-full bg-stone-100 px-3 py-2 text-xs font-semibold text-stone-700">Edit</a>
                                        <form method="POST" action="{{ route('pemilik.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700" onclick="return confirm('Hapus pemilik ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-stone-500">Belum ada data pemilik.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pemilik->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
