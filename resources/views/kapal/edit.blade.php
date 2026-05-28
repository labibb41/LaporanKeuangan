<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('kapal.index') }}"
               class="btn-icon border border-stone-200 bg-white text-stone-500 hover:bg-stone-100 hover:text-stone-800" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="page-label">Edit Master Data</p>
                <h2 class="page-title">Ubah Data Kapal</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <section class="card">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600" style="width: 36px; height: 36px;">
                    <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-stone-900">{{ $kapal->nama_kapal }}</h3>
                    <p class="text-[10px] text-stone-400">Perbarui informasi kapal ini</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kapal.update', $kapal) }}" class="space-y-3">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-3">
                    {{-- Nama Kapal --}}
                    <div class="col-span-2 md:col-span-1">
                        <label for="nama_kapal" class="label">Nama Kapal <span class="text-rose-500">*</span></label>
                        <input id="nama_kapal" name="nama_kapal" type="text"
                            value="{{ old('nama_kapal', $kapal->nama_kapal) }}"
                            class="field !py-1.5" required>
                        @error('nama_kapal')
                            <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Paguyuban --}}
                    @php
                        $existingPaguyuban = old('nama_paguyuban', $kapal->nama_paguyuban);
                        $isCustomPaguyuban = $existingPaguyuban && !($daftarPaguyuban ?? collect())->contains($existingPaguyuban);
                    @endphp
                    <div x-data="{ mode: '{{ $isCustomPaguyuban ? 'input' : 'select' }}', val: '{{ addslashes($existingPaguyuban) }}' }" class="col-span-2 md:col-span-1">
                        <label for="nama_paguyuban" class="label">Paguyuban</label>
                        
                        <div x-show="mode === 'select'">
                            <select name="nama_paguyuban" class="field w-full !py-1.5" x-model="val" :disabled="mode !== 'select'"
                                    @change="if($event.target.value === 'NEW') { mode = 'input'; val = ''; }">
                                <option value="">-- Tanpa Paguyuban --</option>
                                @foreach ($daftarPaguyuban ?? [] as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                                <option value="NEW" class="font-bold text-blue-600">+ Tambah paguyuban baru</option>
                            </select>
                        </div>

                        <div x-cloak x-show="mode === 'input'" class="flex gap-2">
                            <input type="text" name="nama_paguyuban" class="field w-full !py-1.5" placeholder="Ketik nama paguyuban..." x-model="val" :disabled="mode !== 'input'">
                            <button type="button" @click="mode = 'select'; val = ''" class="btn-soft px-3 py-1.5 text-[11px] font-bold shrink-0">Batal</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    {{-- Kapasitas --}}
                    <div>
                        <label for="kapasitas_ton" class="label">Kapasitas (ton)</label>
                        <input id="kapasitas_ton" name="kapasitas_ton" type="number"
                            min="0" step="0.01"
                            value="{{ old('kapasitas_ton', (float) $kapal->kapasitas_ton) }}"
                            class="field !py-1.5">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    {{-- Tarif Tonase --}}
                    <div>
                        <label for="tarif_tonase" class="label">Tarif Telly (Rp/ton)</label>
                        <input id="tarif_tonase" name="tarif_tonase" type="number"
                            min="0" step="0.01"
                            value="{{ old('tarif_tonase', (float) $kapal->tarif_tonase) }}"
                            class="field !py-1.5">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="label">Status</label>
                        <select id="status" name="status" class="field !py-1.5">
                            <option value="aktif" @selected(old('status', $kapal->status ?? 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $kapal->status) === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="label">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="2"
                        class="field resize-none !py-1.5">{{ old('keterangan', $kapal->keterangan) }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-2 pt-1.5">
                    <button type="submit" class="btn-primary flex items-center gap-1.5 py-2">
                        <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('kapal.index') }}" class="btn-soft py-2">Kembali</a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
