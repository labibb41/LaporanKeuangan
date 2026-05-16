<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-label">Master Data</p>
            <h2 class="page-title">Data Karyawan</h2>
            <p class="page-sub">Kelola data telly dan karyawan beserta riwayat gaji per periode.</p>
        </div>
    </x-slot>

    {{-- ── Filter Periode ────────────────────────────────────── --}}
    <form method="GET" action="{{ route('karyawan.index') }}"
        class="mb-6 card flex flex-wrap items-end gap-4">
        <div class="flex items-center gap-3 mr-2">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100">
                <svg class="h-4.5 w-4.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
            </div>
            <span class="font-semibold text-stone-700 text-sm">Filter Periode</span>
        </div>
        <div>
            <label for="bulan" class="label">Bulan</label>
            <input id="bulan" name="bulan" type="number" min="1" max="12"
                value="{{ $bulan }}" class="field-white w-24">
        </div>
        <div>
            <label for="tahun" class="label">Tahun</label>
            <input id="tahun" name="tahun" type="number" min="2020"
                value="{{ $tahun }}" class="field-white w-28">
        </div>
        <button type="submit" class="btn-primary btn-compact">Terapkan</button>
        <p class="ml-2 hidden text-xs text-stone-400 sm:block">Total gaji periode mengikuti filter di atas.</p>
    </form>

    <div class="grid gap-6 xl:grid-cols-[360px_1fr]">

        {{-- ── FORM TAMBAH ─────────────────────────────────────── --}}
        <section class="card self-start">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-100">
                    <svg class="h-5 w-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-stone-950">Tambah Karyawan</h3>
                    <p class="text-xs text-stone-400">Isi data karyawan baru</p>
                </div>
            </div>

            <form method="POST" action="{{ route('karyawan.store') }}" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="nama" class="label">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input id="nama" name="nama" type="text"
                        value="{{ old('nama') }}"
                        placeholder="cth. Budi Santoso"
                        class="field" required>
                    @error('nama')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="label">No. HP / WhatsApp</label>
                    <input id="no_hp" name="no_hp" type="text"
                        value="{{ old('no_hp') }}"
                        placeholder="08xxxxxxxxxx"
                        class="field">
                </div>

                {{-- Jabatan --}}
                <div>
                    <label for="jabatan" class="label">Jabatan</label>
                    <input id="jabatan" name="jabatan" type="text"
                        value="{{ old('jabatan') }}"
                        placeholder="cth. Telly, Supervisor"
                        class="field">
                </div>

                {{-- Status & Tanggal --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="status_kar" class="label">Status</label>
                        <select id="status_kar" name="status" class="field">
                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_bergabung" class="label">Tgl Bergabung</label>
                        <input id="tanggal_bergabung" name="tanggal_bergabung" type="date"
                            value="{{ old('tanggal_bergabung') }}"
                            class="field">
                    </div>
                </div>

                <div class="divider"></div>
                <p class="text-xs font-bold uppercase tracking-widest text-stone-400">Dokumen & Pajak</p>

                {{-- KTP & NPWP --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="ktp" class="label">No. KTP</label>
                        <input id="ktp" name="ktp" type="text"
                            value="{{ old('ktp') }}"
                            placeholder="16 digit"
                            class="field">
                    </div>
                    <div>
                        <label for="npwp" class="label">No. NPWP</label>
                        <input id="npwp" name="npwp" type="text"
                            value="{{ old('npwp') }}"
                            placeholder="XX.XXX.XXX.X-XXX"
                            class="field">
                    </div>
                </div>

                {{-- Tarif & PPh --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="tarif_telly" class="label">Tarif Telly (Rp/ritase)</label>
                        <input id="tarif_telly" name="tarif_telly" type="number"
                            min="0" step="0.01"
                            value="{{ old('tarif_telly', 0) }}"
                            class="field">
                    </div>
                    <div>
                        <label for="pph_persen" class="label">PPh Default (%)</label>
                        <input id="pph_persen" name="pph_persen" type="number"
                            min="0" step="0.01"
                            value="{{ old('pph_persen', 0) }}"
                            class="field">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="label">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2"
                        placeholder="Alamat lengkap karyawan"
                        class="field resize-none">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Karyawan
                </button>
            </form>
        </section>

        {{-- ── DAFTAR KARYAWAN ──────────────────────────────────── --}}
        <section class="card min-w-0">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="section-label">Daftar Karyawan</p>
                    <h3 class="section-title text-base">Karyawan & riwayat gaji</h3>
                </div>
                <span class="badge-stone text-sm px-4 py-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    {{ $karyawan->total() }} karyawan
                </span>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Kontak</th>
                            <th>Dokumen</th>
                            <th class="text-right">Tarif Default</th>
                            <th class="text-center">Aktivitas</th>
                            <th class="text-right">Gaji (Periode)</th>
                            <th class="text-right">Gaji (Semua)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawan as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-black text-indigo-700">
                                            {{ strtoupper(str($item->nama)->take(1)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-stone-900 text-sm">{{ $item->nama }}</p>
                                            <p class="text-xs text-stone-500">{{ $item->jabatan ?: '—' }}</p>
                                            @if($item->tanggal_bergabung)
                                                <p class="text-xs text-stone-400">Sejak {{ $item->tanggal_bergabung->format('M Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2 pl-10">
                                        @if(($item->status ?? 'aktif') === 'aktif')
                                            <span class="badge-green text-[10px]">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @else
                                            <span class="badge-stone text-[10px]">
                                                <span class="h-1.5 w-1.5 rounded-full bg-stone-400"></span> Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-xs">
                                    @if($item->no_hp)
                                        <span class="flex items-center gap-1 text-stone-600 font-medium">
                                            <svg class="h-3.5 w-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            {{ $item->no_hp }}
                                        </span>
                                    @else
                                        <span class="text-stone-300">—</span>
                                    @endif
                                </td>
                                <td class="text-xs text-stone-500">
                                    <p>KTP: <span class="font-medium text-stone-700">{{ $item->ktp ?: '—' }}</span></p>
                                    <p>NPWP: <span class="font-medium text-stone-700">{{ $item->npwp ?: '—' }}</span></p>
                                </td>
                                <td class="text-right text-xs">
                                    <p class="font-semibold text-stone-700">Rp {{ number_format((float) $item->tarif_telly, 0, ',', '.') }}</p>
                                    <p class="text-stone-400">PPh {{ number_format((float) $item->pph_persen, 2, ',', '.') }}%</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-sm font-semibold text-stone-700">{{ $item->transaksi_telly_count }}</p>
                                    <p class="text-xs text-stone-400">transaksi</p>
                                </td>
                                <td class="text-right text-xs">
                                    <p class="text-stone-500">Kotor: <span class="font-semibold text-stone-700">Rp {{ number_format((float) ($item->total_gaji_kotor_bulanan ?? 0), 0, ',', '.') }}</span></p>
                                    <p class="text-stone-500">Bersih: <span class="font-semibold text-emerald-700">Rp {{ number_format((float) ($item->total_gaji_bersih_bulanan ?? 0), 0, ',', '.') }}</span></p>
                                </td>
                                <td class="text-right text-xs">
                                    <p class="text-stone-500">Kotor: <span class="font-semibold text-stone-700">Rp {{ number_format((float) ($item->total_gaji_kotor ?? 0), 0, ',', '.') }}</span></p>
                                    <p class="text-stone-500">Bersih: <span class="font-semibold text-emerald-700">Rp {{ number_format((float) ($item->total_gaji_bersih ?? 0), 0, ',', '.') }}</span></p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('karyawan.edit', $item) }}"
                                           class="btn-icon bg-stone-100 text-stone-600 hover:bg-sky-100 hover:text-sky-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('karyawan.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700"
                                                onclick="return confirm('Hapus karyawan {{ $item->nama }}?')">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-stone-500">Belum ada data karyawan.</p>
                                        <p class="mt-1 text-xs text-stone-400">Tambahkan karyawan melalui form di sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($karyawan->hasPages())
                <div class="mt-5 border-t border-stone-100 pt-5">
                    {{ $karyawan->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
