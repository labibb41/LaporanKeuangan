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
        class="mb-4 card flex flex-wrap items-end gap-3 py-3">
        <div class="flex items-center gap-2 mr-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600" style="width: 32px; height: 32px;">
                <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
            </div>
            <span class="font-bold text-stone-700 text-xs">Filter Periode</span>
        </div>
        <div>
            <label for="bulan" class="label">Bulan</label>
            <select id="bulan" name="bulan" class="field-white min-w-32 !py-1.5">
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $namaBulan)
                    <option value="{{ $idx + 1 }}" @selected($bulan == $idx + 1)>{{ $namaBulan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tahun" class="label">Tahun</label>
            <input id="tahun" name="tahun" type="number" min="2020" value="{{ $tahun }}" class="field-white w-24 !py-1.5">
        </div>
        <button type="submit" class="btn-primary btn-compact flex items-center gap-1">
            <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Terapkan
        </button>
        <p class="ml-2 hidden text-[11px] text-stone-400 sm:block">Total gaji periode mengikuti filter di atas.</p>
    </form>

    {{-- Side-by-side grid layout on larger screens --}}
    <div class="grid gap-4 lg:grid-cols-3 items-start"
         x-data="{
             showEditModal: false,
             editData: {
                 id: '',
                 nama: '',
                 no_hp: '',
                 alamat: '',
                 tanggal_bergabung: '',
                 status: 'aktif',
                 jabatan: '',
                 ktp: '',
                 npwp: '',
                 tarif_telly: 0,
                 pph_persen: 0
             },
             itemsList: {{ json_encode($karyawan->items()) }},
             openEdit(item) {
                 this.editData = {
                     id: item.id,
                     nama: item.nama || '',
                     no_hp: item.no_hp || '',
                     alamat: item.alamat || '',
                     tanggal_bergabung: item.tanggal_bergabung ? item.tanggal_bergabung.substring(0, 10) : '',
                     status: item.status || 'aktif',
                     jabatan: item.jabatan || '',
                     ktp: item.ktp || '',
                     npwp: item.npwp || '',
                     tarif_telly: item.tarif_telly || 0,
                     pph_persen: item.pph_persen || 0
                 };
                 this.showEditModal = true;
             },
             init() {
                 @if(old('id') && $errors->any())
                     this.editData = {
                         id: '{{ old('id') }}',
                         nama: '{{ old('nama') }}',
                         no_hp: '{{ old('no_hp') }}',
                         alamat: '{{ old('alamat') }}',
                         tanggal_bergabung: '{{ old('tanggal_bergabung') }}',
                         status: '{{ old('status', 'aktif') }}',
                         jabatan: '{{ old('jabatan') }}',
                         ktp: '{{ old('ktp') }}',
                         npwp: '{{ old('npwp') }}',
                         tarif_telly: '{{ old('tarif_telly', 0) }}',
                         pph_persen: '{{ old('pph_persen', 0) }}'
                     };
                     this.showEditModal = true;
                 @endif
             }
         }">

        {{-- ── FORM TAMBAH (1 column on large screens) ───────────────── --}}
        <section class="card lg:col-span-1">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600" style="width: 36px; height: 36px;">
                    <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider">Tambah Karyawan</h3>
                    <p class="text-[10px] text-stone-400">Isi data karyawan baru</p>
                </div>
            </div>

            <form method="POST" action="{{ route('karyawan.store') }}" class="space-y-3">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="nama" class="label">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input id="nama" name="nama" type="text"
                        value="{{ old('nama') }}"
                        placeholder="cth. Budi Santoso"
                        class="field !py-1.5" required>
                    @error('nama')<p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>@enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="label">No. HP / WhatsApp</label>
                    <input id="no_hp" name="no_hp" type="text"
                        value="{{ old('no_hp') }}"
                        placeholder="08xxxxxxxxxx"
                        class="field !py-1.5">
                </div>

                {{-- Jabatan --}}
                <div>
                    <label for="jabatan" class="label">Jabatan</label>
                    <input id="jabatan" name="jabatan" type="text"
                        value="{{ old('jabatan') }}"
                        placeholder="cth. Telly, Supervisor"
                        class="field !py-1.5">
                </div>

                {{-- Status & Tanggal --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="status_kar" class="label">Status</label>
                        <select id="status_kar" name="status" class="field !py-1.5">
                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_bergabung" class="label">Tgl Bergabung</label>
                        <input id="tanggal_bergabung" name="tanggal_bergabung" type="date"
                            value="{{ old('tanggal_bergabung') }}"
                            class="field !py-1.5">
                    </div>
                </div>

                <div class="divider !my-2"></div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Dokumen & Pajak</p>

                {{-- KTP & NPWP --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="ktp" class="label">No. KTP</label>
                        <input id="ktp" name="ktp" type="text"
                            value="{{ old('ktp') }}"
                            placeholder="16 digit"
                            class="field !py-1.5">
                    </div>
                    <div>
                        <label for="npwp" class="label">No. NPWP</label>
                        <input id="npwp" name="npwp" type="text"
                            value="{{ old('npwp') }}"
                            placeholder="XX.XXX.XXX.X-XXX"
                            class="field !py-1.5">
                    </div>
                </div>

                {{-- Tarif & PPh --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="tarif_telly" class="label">Tarif Telly (Rp/ritase)</label>
                        <input id="tarif_telly" name="tarif_telly" type="number"
                            min="0" step="0.01"
                            value="{{ old('tarif_telly', 0) }}"
                            class="field !py-1.5">
                    </div>
                    <div>
                        <label for="pph_persen" class="label">PPh Default (%)</label>
                        <input id="pph_persen" name="pph_persen" type="number"
                            min="0" step="0.01"
                            value="{{ old('pph_persen', 0) }}"
                            class="field !py-1.5">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="label">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2"
                        placeholder="Alamat lengkap karyawan"
                        class="field resize-none !py-1.5">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full justify-center flex items-center gap-1.5 py-2">
                    <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Karyawan
                </button>
            </form>
        </section>

        {{-- ── DAFTAR KARYAWAN (2 columns on large screens) ───────────── --}}
        <section class="card min-w-0 lg:col-span-2">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="section-label">Daftar Karyawan</p>
                    <h3 class="section-title text-sm">Karyawan & riwayat gaji</h3>
                </div>
                <span class="badge-stone text-[11px] px-3 py-1 flex items-center gap-1.5 font-semibold">
                    <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        @forelse ($karyawan as $index => $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 text-[11px] font-black text-blue-600" style="width: 28px; height: 28px; min-width: 28px; min-height: 28px;">
                                            {{ strtoupper(str($item->nama)->take(1)) }}
                                        </span>
                                        <div>
                                            <p class="font-bold text-stone-900 text-xs">{{ $item->nama }}</p>
                                            <p class="text-[10px] text-stone-500 leading-none mt-0.5">{{ $item->jabatan ?: '—' }}</p>
                                            @if($item->tanggal_bergabung)
                                                <p class="text-[9px] text-stone-400 mt-0.5">Sejak {{ $item->tanggal_bergabung->format('M Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-1.5 pl-9">
                                        @if(($item->status ?? 'aktif') === 'aktif')
                                            <span class="badge-green text-[9px] border border-emerald-100 px-1.5 py-0.5">
                                                <span class="h-1 w-1 rounded-full bg-emerald-500" style="width: 4px; height: 4px;"></span> Aktif
                                            </span>
                                        @else
                                            <span class="badge-stone text-[9px] border border-stone-200 px-1.5 py-0.5">
                                                <span class="h-1 w-1 rounded-full bg-stone-400" style="width: 4px; height: 4px;"></span> Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($item->no_hp)
                                        <span class="flex items-center gap-1 text-stone-600 font-semibold text-[11px]">
                                            <svg class="h-3.5 w-3.5 text-stone-400" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            {{ $item->no_hp }}
                                        </span>
                                    @else
                                        <span class="text-stone-300">—</span>
                                    @endif
                                </td>
                                <td class="text-[10px] text-stone-500">
                                    <p>KTP: <span class="font-semibold text-stone-700">{{ $item->ktp ?: '—' }}</span></p>
                                    <p class="mt-0.5">NPWP: <span class="font-semibold text-stone-700">{{ $item->npwp ?: '—' }}</span></p>
                                </td>
                                <td class="text-right">
                                    <p class="font-bold text-stone-800">Rp {{ number_format((float) $item->tarif_telly, 0, ',', '.') }}</p>
                                    <p class="text-[10px] text-stone-400 mt-0.5">PPh {{ number_format((float) $item->pph_persen, 2, ',', '.') }}%</p>
                                </td>
                                <td class="text-center">
                                    <p class="font-bold text-stone-800">{{ $item->transaksi_telly_count }}</p>
                                    <p class="text-[10px] text-stone-400">ritase</p>
                                </td>
                                <td class="text-right text-[11px]">
                                    <p class="text-stone-500">Kotor: <span class="font-semibold text-stone-700">Rp {{ number_format((float) ($item->total_gaji_kotor_bulanan ?? 0), 0, ',', '.') }}</span></p>
                                    <p class="text-stone-500 mt-0.5">Bersih: <span class="font-bold text-emerald-600">Rp {{ number_format((float) ($item->total_gaji_bersih_bulanan ?? 0), 0, ',', '.') }}</span></p>
                                </td>
                                <td class="text-right text-[11px]">
                                    <p class="text-stone-500">Kotor: <span class="font-semibold text-stone-700">Rp {{ number_format((float) ($item->total_gaji_kotor ?? 0), 0, ',', '.') }}</span></p>
                                    <p class="text-stone-500 mt-0.5">Bersih: <span class="font-bold text-emerald-600">Rp {{ number_format((float) ($item->total_gaji_bersih ?? 0), 0, ',', '.') }}</span></p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <button type="button"
                                            @click="openEdit(itemsList[{{ $index }}])"
                                           class="btn-icon bg-stone-50 text-stone-600 hover:bg-blue-50 hover:text-blue-600" style="width: 26px; height: 26px;">
                                            <svg class="h-4 w-4" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('karyawan.destroy', $item) }}" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700" style="width: 26px; height: 26px;"
                                                @click="confirmHapusKaryawan('{{ addslashes($item->nama) }}', document.getElementById('delete-form-{{ $item->id }}'))">
                                                <svg class="h-4 w-4" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <div class="empty-state-icon" style="width: 32px; height: 32px;">
                                            <svg class="h-8 w-8" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-stone-500">Belum ada data karyawan.</p>
                                        <p class="mt-1 text-[10px] text-stone-400">Tambahkan karyawan melalui form di sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($karyawan->hasPages())
                <div class="mt-4 border-t border-stone-100 pt-4">
                    {{ $karyawan->links() }}
                </div>
            @endif
        </section>

        <!-- FORM MODAL (EDIT) -->
        <div
            x-show="showEditModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md"
            style="display: none;"
            x-cloak
        >
            <div
                x-show="showEditModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white border border-stone-200 shadow-2xl p-6 sm:p-8"
                @click.outside="showEditModal = false"
            >
                <!-- Close Button -->
                <button
                    type="button"
                    @click="showEditModal = false"
                    class="absolute top-4 right-4 text-stone-400 hover:text-stone-600 transition-colors"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="mb-5 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-700" style="width: 40px; height: 40px;">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-black text-stone-900" style="font-family: 'Montserrat', sans-serif;">Ubah Data Karyawan</h3>
                        <p class="text-xs text-stone-500">Perbarui informasi karyawan ini</p>
                    </div>
                </div>

                <form method="POST" :action="'/karyawan/' + editData.id" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" :value="editData.id">

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Nama --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="edit_nama" class="label">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input id="edit_nama" name="nama" type="text"
                                x-model="editData.nama"
                                class="field !py-1.5" required>
                            @error('nama')<p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        {{-- No HP --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="edit_no_hp" class="label">No. HP / WhatsApp</label>
                            <input id="edit_no_hp" name="no_hp" type="text"
                                x-model="editData.no_hp"
                                class="field !py-1.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Jabatan --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="edit_jabatan" class="label">Jabatan</label>
                            <input id="edit_jabatan" name="jabatan" type="text"
                                x-model="editData.jabatan"
                                class="field !py-1.5">
                        </div>

                        {{-- Status & Tanggal --}}
                        <div class="col-span-2 sm:col-span-1 grid grid-cols-2 gap-2">
                            <div>
                                <label for="edit_status" class="label">Status</label>
                                <select id="edit_status" name="status" class="field !py-1.5" x-model="editData.status">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                            <div>
                                <label for="edit_tanggal_bergabung" class="label">Tgl Bergabung</label>
                                <input id="edit_tanggal_bergabung" name="tanggal_bergabung" type="date"
                                    x-model="editData.tanggal_bergabung"
                                    class="field !py-1.5">
                            </div>
                        </div>
                    </div>

                    <div class="divider !my-3"></div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Dokumen & Pajak</p>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- KTP & NPWP --}}
                        <div>
                            <label for="edit_ktp" class="label">No. KTP</label>
                            <input id="edit_ktp" name="ktp" type="text"
                                x-model="editData.ktp"
                                class="field !py-1.5">
                        </div>
                        <div>
                            <label for="edit_npwp" class="label">No. NPWP</label>
                            <input id="edit_npwp" name="npwp" type="text"
                                x-model="editData.npwp"
                                class="field !py-1.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Tarif & PPh --}}
                        <div>
                            <label for="edit_tarif_telly" class="label">Tarif Telly (Rp/ritase)</label>
                            <input id="edit_tarif_telly" name="tarif_telly" type="number"
                                min="0" step="0.01"
                                x-model="editData.tarif_telly"
                                class="field !py-1.5">
                        </div>
                        <div>
                            <label for="edit_pph_persen" class="label">PPh Default (%)</label>
                            <input id="edit_pph_persen" name="pph_persen" type="number"
                                min="0" step="0.01"
                                x-model="editData.pph_persen"
                                class="field !py-1.5">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="edit_alamat" class="label">Alamat</label>
                        <textarea id="edit_alamat" name="alamat" rows="2"
                            x-model="editData.alamat"
                            class="field resize-none !py-1.5"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showEditModal = false" class="btn-soft py-2">Batal</button>
                        <button type="submit" class="btn-primary flex items-center gap-1.5 py-2">
                            <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
function confirmHapusKaryawan(nama, formEl) {
    Swal.fire({
        title: 'Hapus Karyawan?',
        html: `<div style="text-align:left; font-size:13px; color:#57534e; line-height:1.6;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <span style="font-weight:600; color:#292524;">Nama Lengkap</span>
                <span style="color:#a8a29e;">—</span>
                <span>${nama}</span>
            </div>
            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:10px 12px; font-size:12px; color:#be123c;">
                ⚠️ Data karyawan ini akan dihapus <strong>permanen</strong> dari database.
            </div>
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#78716c',
        confirmButtonText: '<svg xmlns="http://www.w3.org/2000/svg" style="display:inline;width:14px;height:14px;margin-right:6px;vertical-align:-2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup:         'rounded-2xl shadow-2xl',
            title:         'font-black text-stone-900',
            confirmButton: 'rounded-xl font-bold px-5 py-2.5',
            cancelButton:  'rounded-xl font-semibold px-5 py-2.5',
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            formEl.submit();
        }
    });
}
</script>
</x-app-layout>
