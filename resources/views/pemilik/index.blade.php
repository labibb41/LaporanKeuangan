<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-label">Master Data</p>
            <h2 class="page-title">Data Pemilik Kendaraan</h2>
            <p class="page-sub">Kelola pemilik armada kendaraan dan rincian transaksi mereka.</p>
        </div>
    </x-slot>

    {{-- Side-by-side grid layout on larger screens --}}
    <div class="grid gap-4 lg:grid-cols-3 items-start"
         x-data="{
             showEditModal: false,
             editData: {
                 id: '',
                 nama_pemilik: ''
             },
             itemsList: {{ json_encode($pemilik->items()) }},
             openEdit(item) {
                 this.editData = {
                     id: item.id,
                     nama_pemilik: item.nama_pemilik || ''
                 };
                 this.showEditModal = true;
             },
             init() {
                 @if(old('id') && $errors->any())
                     this.editData = {
                         id: '{{ old('id') }}',
                         nama_pemilik: '{{ old('nama_pemilik') }}'
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
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider">Tambah Pemilik</h3>
                    <p class="text-[10px] text-stone-400">Isi data pemilik baru</p>
                </div>
            </div>

            <form method="POST" action="{{ route('pemilik.store') }}" class="space-y-3">
                @csrf

                {{-- Nama Pemilik --}}
                <div>
                    <label for="nama_pemilik" class="label">Nama Pemilik <span class="text-rose-500">*</span></label>
                    <input id="nama_pemilik" name="nama_pemilik" type="text"
                        value="{{ old('nama_pemilik') }}"
                        placeholder="cth. H. Ahmad"
                        class="field !py-1.5" required>
                    @error('nama_pemilik')
                        <p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full justify-center flex items-center gap-1.5 py-2">
                    <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pemilik
                </button>
            </form>
        </section>

        {{-- ── DAFTAR PEMILIK (2 columns on large screens) ───────────── --}}
        <section class="card min-w-0 lg:col-span-2">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="section-label">Daftar Pemilik</p>
                    <h3 class="section-title text-sm">Master pemilik aktif</h3>
                </div>
                <span class="badge-stone text-[11px] px-3 py-1 flex items-center gap-1.5 font-semibold">
                    <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    {{ $pemilik->total() }} pemilik
                </span>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Pemilik</th>
                            <th class="text-center">Total Kendaraan</th>
                            <th class="text-right">Pendapatan Bruto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemilik as $index => $item)
                            <tr>
                                <td>
                                    <p class="font-bold text-stone-900 text-xs">{{ $item->nama_pemilik }}</p>
                                </td>
                                <td class="text-center">
                                    <span class="badge-blue text-[9px] border border-blue-100 font-bold px-2 py-0.5">{{ $item->kendaraan_count }} unit</span>
                                </td>
                                <td class="text-right font-bold text-emerald-600 text-xs whitespace-nowrap">
                                    Rp {{ number_format((float) ($item->transaksi_operasional_sum_pendapatan ?? 0), 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('pemilik.show', $item) }}"
                                           class="btn-primary btn-soft text-[10px] font-bold px-2.5 py-1" style="line-height: normal;">
                                            Armada
                                        </a>
                                        <button type="button"
                                            @click="openEdit(itemsList[{{ $index }}])"
                                            class="btn-icon bg-stone-50 text-stone-600 hover:bg-blue-50 hover:text-blue-600" style="width: 26px; height: 26px;">
                                            <svg class="h-4 w-4" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('pemilik.destroy', $item) }}" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700" style="width: 26px; height: 26px;"
                                                @click="confirmHapusPemilik('{{ addslashes($item->nama_pemilik) }}', document.getElementById('delete-form-{{ $item->id }}'))">
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-stone-500">Belum ada data pemilik.</p>
                                        <p class="mt-1 text-[10px] text-stone-400">Tambahkan pemilik melalui form di sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pemilik->hasPages())
                <div class="mt-4 border-t border-stone-100 pt-4">
                    {{ $pemilik->links() }}
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
                        <h3 class="text-base font-black text-stone-900" style="font-family: 'Montserrat', sans-serif;">Ubah Data Pemilik</h3>
                        <p class="text-xs text-stone-500">Perbarui informasi pemilik ini</p>
                    </div>
                </div>

                <form method="POST" :action="'/pemilik/' + editData.id" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" :value="editData.id">

                    {{-- Nama Pemilik --}}
                    <div>
                        <label for="edit_nama_pemilik" class="label">Nama Pemilik <span class="text-rose-500">*</span></label>
                        <input id="edit_nama_pemilik" name="nama_pemilik" type="text"
                            x-model="editData.nama_pemilik"
                            class="field !py-1.5" required>
                        @error('nama_pemilik')
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
function confirmHapusPemilik(namaPemilik, formEl) {
    Swal.fire({
        title: 'Hapus Pemilik?',
        html: `<div style="text-align:left; font-size:13px; color:#57534e; line-height:1.6;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <span style="font-weight:600; color:#292524;">Nama Pemilik</span>
                <span style="color:#a8a29e;">—</span>
                <span>${namaPemilik}</span>
            </div>
            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:10px 12px; font-size:12px; color:#be123c;">
                ⚠️ Data pemilik ini akan dihapus <strong>permanen</strong> dari database.
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
