<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('karyawan.index') }}"
               class="btn-icon border border-stone-200 bg-white text-stone-500 hover:bg-stone-100 hover:text-stone-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.35em] text-amber-700">Edit Master</p>
                <h2 class="page-title">Ubah Data Karyawan</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <section class="card">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-lg font-black text-indigo-700">
                    {{ strtoupper(str($karyawan->nama)->take(1)) }}
                </span>
                <div>
                    <h3 class="text-base font-black text-stone-950">{{ $karyawan->nama }}</h3>
                    <p class="text-xs text-stone-400">{{ $karyawan->jabatan ?: 'Karyawan' }} · Perbarui informasi</p>
                </div>
            </div>

            <form method="POST" action="{{ route('karyawan.update', $karyawan) }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Informasi Utama --}}
                <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Informasi Utama</p>

                <div class="grid gap-4 md:grid-cols-2">
                    {{-- Nama --}}
                    <div class="md:col-span-2">
                        <label for="nama" class="label">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input id="nama" name="nama" type="text"
                            value="{{ old('nama', $karyawan->nama) }}"
                            class="field" required>
                        @error('nama')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="label">No. HP / WhatsApp</label>
                        <input id="no_hp" name="no_hp" type="text"
                            value="{{ old('no_hp', $karyawan->no_hp) }}"
                            placeholder="08xxxxxxxxxx"
                            class="field">
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label for="jabatan" class="label">Jabatan</label>
                        <input id="jabatan" name="jabatan" type="text"
                            value="{{ old('jabatan', $karyawan->jabatan) }}"
                            placeholder="cth. Telly, Supervisor"
                            class="field">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status_kar" class="label">Status</label>
                        <select id="status_kar" name="status" class="field">
                            <option value="aktif" @selected(old('status', $karyawan->status ?? 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $karyawan->status) === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>

                    {{-- Tanggal Bergabung --}}
                    <div>
                        <label for="tanggal_bergabung" class="label">Tanggal Bergabung</label>
                        <input id="tanggal_bergabung" name="tanggal_bergabung" type="date"
                            value="{{ old('tanggal_bergabung', $karyawan->tanggal_bergabung?->format('Y-m-d')) }}"
                            class="field">
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="label">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2"
                            class="field resize-none">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    </div>
                </div>

                <div class="divider"></div>
                <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Dokumen & Pajak</p>

                <div class="grid gap-4 md:grid-cols-2">
                    {{-- KTP --}}
                    <div>
                        <label for="ktp" class="label">No. KTP</label>
                        <input id="ktp" name="ktp" type="text"
                            value="{{ old('ktp', $karyawan->ktp) }}"
                            placeholder="16 digit"
                            class="field">
                    </div>

                    {{-- NPWP --}}
                    <div>
                        <label for="npwp" class="label">No. NPWP</label>
                        <input id="npwp" name="npwp" type="text"
                            value="{{ old('npwp', $karyawan->npwp) }}"
                            placeholder="XX.XXX.XXX.X-XXX"
                            class="field">
                    </div>

                    {{-- Tarif Telly --}}
                    <div>
                        <label for="tarif_telly" class="label">Tarif Telly (Rp/ritase)</label>
                        <input id="tarif_telly" name="tarif_telly" type="number"
                            min="0" step="0.01"
                            value="{{ old('tarif_telly', $karyawan->tarif_telly ?? 0) }}"
                            class="field">
                    </div>

                    {{-- PPh --}}
                    <div>
                        <label for="pph_persen" class="label">PPh Default (%)</label>
                        <input id="pph_persen" name="pph_persen" type="number"
                            min="0" step="0.01"
                            value="{{ old('pph_persen', $karyawan->pph_persen ?? 0) }}"
                            class="field">
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('karyawan.index') }}" class="btn-soft">Kembali</a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
