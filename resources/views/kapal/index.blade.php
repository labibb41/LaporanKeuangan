<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-label">Master Data</p>
            <h2 class="page-title">Data Kapal Operasional</h2>
            <p class="page-sub">Kelola armada kapal beserta voyage, tarif, dan status operasional.</p>
        </div>
    </x-slot>

    {{-- Side-by-side grid layout on larger screens --}}
    <div class="grid gap-4 lg:grid-cols-3 items-start"
         x-data="{
             showEditModal: false,
             editData: {
                 id: '',
                 nama_kapal: '',
                 nama_paguyuban: '',
                 kapasitas_ton: '',
                 tarif_tonase: '',
                 status: 'aktif',
                 keterangan: ''
             },
             paguyubanMode: 'select',
             daftarPaguyuban: {{ json_encode($daftarPaguyuban->values()->toArray()) }},
             itemsList: {{ json_encode($kapal->items()) }},
             openEdit(item) {
                 this.editData = {
                     id: item.id,
                     nama_kapal: item.nama_kapal || '',
                     nama_paguyuban: item.nama_paguyuban || '',
                     kapasitas_ton: item.kapasitas_ton || '',
                     tarif_tonase: item.tarif_tonase || '',
                     status: item.status || 'aktif',
                     keterangan: item.keterangan || ''
                 };
                 this.paguyubanMode = (item.nama_paguyuban && !this.daftarPaguyuban.includes(item.nama_paguyuban)) ? 'input' : 'select';
                 this.showEditModal = true;
             },
             init() {
                 @if(old('id') && $errors->any())
                     this.editData = {
                         id: '{{ old('id') }}',
                         nama_kapal: '{{ old('nama_kapal') }}',
                         nama_paguyuban: '{{ old('nama_paguyuban') }}',
                         kapasitas_ton: '{{ old('kapasitas_ton') }}',
                         tarif_tonase: '{{ old('tarif_tonase') }}',
                         status: '{{ old('status', 'aktif') }}',
                         keterangan: '{{ old('keterangan') }}'
                     };
                     this.paguyubanMode = (this.editData.nama_paguyuban && !this.daftarPaguyuban.includes(this.editData.nama_paguyuban)) ? 'input' : 'select';
                     this.showEditModal = true;
                 @endif
             }
         }">

        {{-- ── FORM TAMBAH (1 column on large screens) ───────────────── --}}
        <section class="card lg:col-span-1">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600" style="width: 36px; height: 36px;">
                    <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider">Tambah Kapal</h3>
                    <p class="text-[10px] text-stone-400">Isi data kapal operasional baru</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kapal.store') }}" class="space-y-3">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    {{-- Nama Kapal --}}
                    <div>
                        <label for="nama_kapal" class="label">Nama Kapal <span class="text-rose-500">*</span></label>
                        <input id="nama_kapal" name="nama_kapal" type="text"
                            value="{{ old('nama_kapal') }}"
                            placeholder="cth. KM Permata Jaya"
                            class="field !py-1.5" required>
                        @error('nama_kapal')
                            <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Paguyuban --}}
                    <div x-data="{ mode: 'select', val: '{{ addslashes(old('nama_paguyuban')) }}' }">
                        <label for="nama_paguyuban" class="label">Paguyuban</label>
                        
                        <div x-show="mode === 'select'">
                            <select name="nama_paguyuban" class="field w-full !py-1.5" x-model="val" :disabled="mode !== 'select'"
                                    @change="if($event.target.value === 'NEW') { mode = 'input'; val = ''; }">
                                <option value="">-- Tanpa Paguyuban --</option>
                                @foreach ($daftarPaguyuban ?? [] as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                                <option value="NEW" class="font-bold text-blue-600">+ Tambah baru</option>
                            </select>
                        </div>

                        <div x-cloak x-show="mode === 'input'" class="flex gap-2">
                            <input type="text" name="nama_paguyuban" class="field w-full !py-1.5" placeholder="Ketik paguyuban..." x-model="val" :disabled="mode !== 'input'">
                            <button type="button" @click="mode = 'select'; val = ''" class="btn-soft px-3 py-1.5 text-[11px] shrink-0 font-bold">Batal</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    {{-- Kapasitas --}}
                    <div>
                        <label for="kapasitas_ton" class="label">Kapasitas (ton)</label>
                        <input id="kapasitas_ton" name="kapasitas_ton" type="number"
                            min="0" step="0.01"
                            value="{{ old('kapasitas_ton') }}"
                            placeholder="0"
                            class="field !py-1.5">
                    </div>

                    {{-- Tarif Tonase --}}
                    <div>
                        <label for="tarif_tonase" class="label">Tarif Telly</label>
                        <input id="tarif_tonase" name="tarif_tonase" type="number"
                            min="0" step="0.01"
                            value="{{ old('tarif_tonase', 0) }}"
                            class="field !py-1.5">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="label">Status</label>
                        <select id="status" name="status" class="field !py-1.5">
                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="label">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="1"
                        placeholder="Catatan tambahan (opsional)"
                        class="field resize-none !py-1.5">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full justify-center flex items-center gap-1.5 py-2">
                    <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kapal
                </button>
            </form>
        </section>

        {{-- ── DAFTAR KAPAL (2 columns on large screens) ─────────────── --}}
        <section class="card min-w-0 lg:col-span-2">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="section-label">Daftar Kapal</p>
                    <h3 class="section-title text-sm">Kapal yang tersedia</h3>
                </div>
                <span class="badge-stone text-[11px] px-3 py-1 flex items-center gap-1.5 font-semibold">
                    <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/>
                    </svg>
                    {{ $kapal->total() }} kapal
                </span>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Kapal</th>
                            <th>Paguyuban</th>
                            <th class="text-right">Kapasitas</th>
                            <th class="text-right">Tarif / ton</th>
                            <th>Status</th>
                            <th class="text-center">Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kapal as $index => $item)
                            <tr>
                                <td>
                                    <p class="font-bold text-stone-900 text-xs">{{ $item->nama_kapal }}</p>
                                </td>
                                <td>
                                    @if($item->nama_paguyuban)
                                        <span class="badge-stone text-[10px] px-2 py-0.5">{{ $item->nama_paguyuban }}</span>
                                    @else
                                        <span class="text-stone-300 italic text-[11px]">-</span>
                                    @endif
                                </td>
                                <td class="text-right text-stone-600 text-xs">
                                    @if($item->kapasitas_ton)
                                        {{ number_format((float) $item->kapasitas_ton, 0, ',', '.') }} ton
                                    @else
                                        <span class="text-stone-300">—</span>
                                    @endif
                                </td>
                                <td class="text-right font-bold text-stone-700 text-xs whitespace-nowrap">
                                    Rp {{ number_format((float) $item->tarif_tonase, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if(($item->status ?? 'aktif') === 'aktif')
                                        <span class="badge-green text-[9px] border border-emerald-100 px-1.5 py-0.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" style="width: 4px; height: 4px;"></span> Aktif
                                        </span>
                                    @else
                                        <span class="badge-stone text-[9px] border border-stone-200 px-1.5 py-0.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-stone-400" style="width: 4px; height: 4px;"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge-blue text-[9px] border border-blue-100 font-bold px-2 py-0.5">{{ $item->transaksi_operasional_count }}</span>
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
                                        <form method="POST" action="{{ route('kapal.destroy', $item) }}" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700" style="width: 26px; height: 26px;"
                                                @click="confirmHapusKapal('{{ addslashes($item->nama_kapal) }}', document.getElementById('delete-form-{{ $item->id }}'))">
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
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon" style="width: 32px; height: 32px;">
                                            <svg class="h-8 w-8" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-stone-500">Belum ada data kapal.</p>
                                        <p class="mt-1 text-[10px] text-stone-400">Tambahkan kapal melalui form di sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kapal->hasPages())
                <div class="mt-4 border-t border-stone-100 pt-4">
                    {{ $kapal->links() }}
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
                class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-3xl bg-white border border-stone-200 shadow-2xl p-6 sm:p-8"
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
                        <h3 class="text-base font-black text-stone-900" style="font-family: 'Montserrat', sans-serif;">Ubah Data Kapal</h3>
                        <p class="text-xs text-stone-500">Perbarui informasi kapal operasional ini</p>
                    </div>
                </div>

                <form method="POST" :action="'/kapal/' + editData.id" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" :value="editData.id">

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Nama Kapal --}}
                        <div>
                            <label for="edit_nama_kapal" class="label">Nama Kapal <span class="text-rose-500">*</span></label>
                            <input id="edit_nama_kapal" name="nama_kapal" type="text"
                                x-model="editData.nama_kapal"
                                class="field !py-1.5" required>
                            @error('nama_kapal')
                                <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Paguyuban --}}
                        <div>
                            <label for="edit_nama_paguyuban" class="label">Paguyuban</label>
                            
                            <div x-show="paguyubanMode === 'select'">
                                <select name="nama_paguyuban" class="field w-full !py-1.5" x-model="editData.nama_paguyuban" :disabled="paguyubanMode !== 'select'"
                                        @change="if($event.target.value === 'NEW') { paguyubanMode = 'input'; editData.nama_paguyuban = ''; }">
                                    <option value="">-- Tanpa Paguyuban --</option>
                                    @foreach ($daftarPaguyuban ?? [] as $j)
                                        <option value="{{ $j }}">{{ $j }}</option>
                                    @endforeach
                                    <option value="NEW" class="font-bold text-blue-600">+ Tambah baru</option>
                                </select>
                            </div>

                            <div x-cloak x-show="paguyubanMode === 'input'" class="flex gap-2">
                                <input type="text" name="nama_paguyuban" class="field w-full !py-1.5" placeholder="Ketik paguyuban..." x-model="editData.nama_paguyuban" :disabled="paguyubanMode !== 'input'">
                                <button type="button" @click="paguyubanMode = 'select'; editData.nama_paguyuban = ''" class="btn-soft px-3 py-1.5 text-[11px] font-bold shrink-0">Batal</button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        {{-- Kapasitas --}}
                        <div>
                            <label for="edit_kapasitas_ton" class="label">Kapasitas (ton)</label>
                            <input id="edit_kapasitas_ton" name="kapasitas_ton" type="number"
                                min="0" step="0.01"
                                x-model="editData.kapasitas_ton"
                                class="field !py-1.5">
                        </div>

                        {{-- Tarif Tonase --}}
                        <div>
                            <label for="edit_tarif_tonase" class="label">Tarif Telly</label>
                            <input id="edit_tarif_tonase" name="tarif_tonase" type="number"
                                min="0" step="0.01"
                                x-model="editData.tarif_tonase"
                                class="field !py-1.5">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="edit_status" class="label">Status</label>
                            <select id="edit_status" name="status" class="field !py-1.5" x-model="editData.status">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label for="edit_keterangan" class="label">Keterangan</label>
                        <textarea id="edit_keterangan" name="keterangan" rows="1"
                            x-model="editData.keterangan"
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
function confirmHapusKapal(namaKapal, formEl) {
    Swal.fire({
        title: 'Hapus Kapal?',
        html: `<div style="text-align:left; font-size:13px; color:#57534e; line-height:1.6;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <span style="font-weight:600; color:#292524;">Nama Kapal</span>
                <span style="color:#a8a29e;">—</span>
                <span>${namaKapal}</span>
            </div>
            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:10px 12px; font-size:12px; color:#be123c;">
                ⚠️ Data kapal ini akan dihapus <strong>permanen</strong> dari database.
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
