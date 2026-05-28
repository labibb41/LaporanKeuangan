<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-label">Master Data</p>
            <h2 class="page-title">Data Kendaraan</h2>
            <p class="page-sub">Kelola data kendaraan dan status kepemilikannya.</p>
        </div>
    </x-slot>

    {{-- Side-by-side grid layout on larger screens --}}
    <div class="grid gap-4 lg:grid-cols-3 items-start"
         x-data="{
             showEditModal: false,
             editData: {
                 id: '',
                 nopol: '',
                 pemilik_id: ''
             },
             itemsList: {{ json_encode($kendaraan->items()) }},
             openEdit(item) {
                 this.editData = {
                     id: item.id,
                     nopol: item.nopol || '',
                     pemilik_id: item.pemilik_id || ''
                 };
                 this.showEditModal = true;
             },
             init() {
                 @if(old('id') && $errors->any())
                     this.editData = {
                         id: '{{ old('id') }}',
                         nopol: '{{ old('nopol') }}',
                         pemilik_id: '{{ old('pemilik_id') }}'
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider">Tambah Kendaraan</h3>
                    <p class="text-[10px] text-stone-400">Isi data kendaraan baru</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kendaraan.store') }}" class="space-y-3">
                @csrf

                {{-- Nopol --}}
                <div>
                    <label for="nopol" class="label">Nomor Polisi <span class="text-rose-500">*</span></label>
                    <input id="nopol" name="nopol" type="text"
                        value="{{ old('nopol') }}"
                        placeholder="cth. BP 1234 XY"
                        class="field !py-1.5" required>
                    @error('nopol')
                        <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pemilik --}}
                <div>
                    <label for="pemilik_id" class="label">Pemilik <span class="text-rose-500">*</span></label>
                    <select id="pemilik_id" name="pemilik_id" class="field w-full !py-1.5" required>
                        <option value="" disabled selected>Pilih pemilik...</option>
                        @foreach ($daftarPemilik as $pemilik)
                            <option value="{{ $pemilik->id }}" @selected(old('pemilik_id') == $pemilik->id)>{{ $pemilik->nama_pemilik }}</option>
                        @endforeach
                    </select>
                    @error('pemilik_id')
                        <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full justify-center flex items-center gap-1.5 py-2">
                    <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kendaraan
                </button>
            </form>
        </section>

        {{-- ── DAFTAR KENDARAAN (2 columns on large screens) ───────────── --}}
        <section class="card min-w-0 lg:col-span-2">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="section-label">Daftar Kendaraan</p>
                    <h3 class="section-title text-sm">Kendaraan dan pemiliknya</h3>
                </div>
                <span class="badge-stone text-[11px] px-3 py-1 flex items-center gap-1.5 font-semibold">
                    <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 104 0M5 17a2 2 0 104 0m-4 0h14a2 2 0 002-2v-3a2 2 0 00-2-2h-1l-2-5H6L4 10H3a2 2 0 00-2 2v3a2 2 0 002 2h2"/>
                    </svg>
                    {{ $kendaraan->total() }} unit
                </span>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nomor Polisi</th>
                            <th>Pemilik</th>
                            <th class="text-center">Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kendaraan as $index => $item)
                            <tr>
                                <td>
                                    <p class="font-bold text-stone-900 text-xs">{{ $item->nopol }}</p>
                                </td>
                                <td>
                                    <span class="text-stone-600 text-xs font-semibold">{{ $item->pemilik->nama_pemilik }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-blue text-[9px] border border-blue-100 font-bold px-2 py-0.5">{{ $item->transaksi_operasional_count }} transaksi</span>
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
                                        <form method="POST" action="{{ route('kendaraan.destroy', $item) }}" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700" style="width: 26px; height: 26px;"
                                                @click="confirmHapusKendaraan('{{ addslashes($item->nopol) }}', document.getElementById('delete-form-{{ $item->id }}'))">
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
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon" style="width: 32px; height: 32px;">
                                            <svg class="h-8 w-8" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 104 0M5 17a2 2 0 104 0m-4 0h14a2 2 0 002-2v-3a2 2 0 00-2-2h-1l-2-5H6L4 10H3a2 2 0 00-2 2v3a2 2 0 002 2h2"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-stone-500">Belum ada data kendaraan.</p>
                                        <p class="mt-1 text-[10px] text-stone-400">Tambahkan kendaraan melalui form di sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kendaraan->hasPages())
                <div class="mt-4 border-t border-stone-100 pt-4">
                    {{ $kendaraan->links() }}
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
                class="relative w-full max-w-md max-h-[90vh] overflow-y-auto rounded-3xl bg-white border border-stone-200 shadow-2xl p-6 sm:p-8"
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
                        <h3 class="text-base font-black text-stone-900" style="font-family: 'Montserrat', sans-serif;">Ubah Data Kendaraan</h3>
                        <p class="text-xs text-stone-500">Perbarui informasi kendaraan ini</p>
                    </div>
                </div>

                <form method="POST" :action="'/kendaraan/' + editData.id" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" :value="editData.id">

                    {{-- Nopol --}}
                    <div>
                        <label for="edit_nopol" class="label">Nomor Polisi <span class="text-rose-500">*</span></label>
                        <input id="edit_nopol" name="nopol" type="text"
                            x-model="editData.nopol"
                            class="field !py-1.5" required>
                        @error('nopol')
                            <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pemilik --}}
                    <div>
                        <label for="edit_pemilik_id" class="label">Pemilik <span class="text-rose-500">*</span></label>
                        <select id="edit_pemilik_id" name="pemilik_id" class="field w-full !py-1.5" x-model="editData.pemilik_id" required>
                            <option value="" disabled>Pilih pemilik...</option>
                            @foreach ($daftarPemilik as $pemilik)
                                <option value="{{ $pemilik->id }}">{{ $pemilik->nama_pemilik }}</option>
                            @endforeach
                        </select>
                        @error('pemilik_id')
                            <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
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
function confirmHapusKendaraan(nopol, formEl) {
    Swal.fire({
        title: 'Hapus Kendaraan?',
        html: `<div style="text-align:left; font-size:13px; color:#57534e; line-height:1.6;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <span style="font-weight:600; color:#292524;">Nomor Polisi</span>
                <span style="color:#a8a29e;">—</span>
                <span>${nopol}</span>
            </div>
            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:10px 12px; font-size:12px; color:#be123c;">
                ⚠️ Data kendaraan ini akan dihapus <strong>permanen</strong> dari database.
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
