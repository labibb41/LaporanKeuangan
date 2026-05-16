<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('kapal.index') }}"
               class="btn-icon border border-stone-200 bg-white text-stone-500 hover:bg-stone-100 hover:text-stone-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.35em] text-amber-700">Edit Master</p>
                <h2 class="page-title">Ubah Data Kapal</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <section class="card">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100">
                    <svg class="h-5 w-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-stone-950">{{ $kapal->nama_kapal }}</h3>
                    <p class="text-xs text-stone-400">Perbarui informasi kapal ini</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kapal.update', $kapal) }}" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Nama Kapal --}}
                <div>
                    <label for="nama_kapal" class="label">Nama Kapal <span class="text-rose-500">*</span></label>
                    <input id="nama_kapal" name="nama_kapal" type="text"
                        value="{{ old('nama_kapal', $kapal->nama_kapal) }}"
                        class="field" required>
                    @error('nama_kapal')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Kapasitas --}}
                    <div>
                        <label for="kapasitas_ton" class="label">Kapasitas (ton)</label>
                        <input id="kapasitas_ton" name="kapasitas_ton" type="number"
                            min="0" step="0.01"
                            value="{{ old('kapasitas_ton', (float) $kapal->kapasitas_ton) }}"
                            class="field">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Tarif Tonase --}}
                    <div>
                        <label for="tarif_tonase" class="label">Tarif Telly (Rp/ton)</label>
                        <input id="tarif_tonase" name="tarif_tonase" type="number"
                            min="0" step="0.01"
                            value="{{ old('tarif_tonase', (float) $kapal->tarif_tonase) }}"
                            class="field">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="label">Status</label>
                        <select id="status" name="status" class="field">
                            <option value="aktif" @selected(old('status', $kapal->status ?? 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $kapal->status) === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="label">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="field resize-none">{{ old('keterangan', $kapal->keterangan) }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('kapal.index') }}" class="btn-soft">Kembali</a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
