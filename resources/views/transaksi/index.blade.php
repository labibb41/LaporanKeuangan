<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="page-label">Database General</p>
                <h2 class="page-title">Data Mentah Operasional</h2>
                <p class="page-sub">Daftar seluruh transaksi operasional yang telah diinput.</p>
            </div>
            <button @click="$dispatch('open-create-modal')" type="button" class="btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Data General
            </button>
        </div>
    </x-slot>

    @php($rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.'))

    <div
        x-data="{
            showFormModal: false,
            isEditMode: false,
            editId: null,
            showPreviewModal: false,
            previewItem: null,
            errors: {},
            isSubmitting: false,
            needsReload: false,
            
            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0,
                }).format(Number(value || 0));
            },
            formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString;
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
            },
            formatDecimal(value) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(Number(value || 0));
            },
            openCreate() {
                this.isEditMode = false;
                this.editId = null;
                this.errors = {};
                this.showFormModal = true;
                this.$nextTick(() => {
                    this.$dispatch('set-form-data', null);
                });
            },
            openEdit(item) {
                this.isEditMode = true;
                this.editId = item.id;
                this.errors = {};
                this.showFormModal = true;
                this.$nextTick(() => {
                    this.$dispatch('set-form-data', item);
                });
            },
            openPreview(item) {
                this.previewItem = item;
                this.showPreviewModal = true;
            },
            closePreview() {
                this.showPreviewModal = false;
                if (this.needsReload) {
                    window.location.reload();
                }
            },
            submitForm(event) {
                this.isSubmitting = true;
                this.errors = {};
                const formData = new FormData(event.target);
                
                const url = this.isEditMode 
                    ? `/transaksi-operasional/${this.editId}` 
                    : '{{ route('transaksi-operasional.store') }}';
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    if (response.status === 422) {
                        return response.json().then(errData => {
                            this.errors = errData.errors || {};
                            if (this.errors.version) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menyimpan!',
                                    text: this.errors.version[0],
                                    confirmButtonText: 'Refresh Halaman',
                                    confirmButtonColor: '#164A41',
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                            if (window.setFormStep) {
                                window.setFormStep('input');
                            }
                            throw new Error('Validation failed');
                        });
                    }
                    throw new Error('Server error');
                })
                .then(data => {
                    this.isSubmitting = false;
                    this.showFormModal = false;
                    
                    Swal.fire({
                        icon: 'success',
                        title: this.isEditMode ? 'Berhasil Diperbarui' : 'Berhasil Disimpan',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true,
                        timerProgressBar: true
                    });
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(err => {
                    this.isSubmitting = false;
                    if (err.message !== 'Validation failed') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memproses data. Silakan coba lagi.'
                        });
                    }
                });
            }
        }"
        x-on:open-create-modal.window="openCreate()"
        x-on:close-form-modal.window="showFormModal = false"
        class="space-y-6"
    >
        {{-- ═══ KPI Summary ═══════════════════════════════════════════ --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="stat-card relative overflow-hidden bg-gradient-to-br from-[#164A41] to-[#4D774E] text-white shadow-lg shadow-emerald-100/50">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-white/80">Baris Data</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7a8 3 0 0116 0M4 7v10a8 3 0 0016 0V7"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-3xl font-black leading-none">{{ $summary['total'] }}</p>
                    <p class="mt-2 text-xs text-white/70">Total data tersimpan</p>
                </div>
            </div>

            <div class="stat-card border border-stone-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-stone-500">Total Trips</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100">
                        <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 104 0M5 17a2 2 0 104 0m-4 0h14a2 2 0 002-2v-3a2 2 0 00-2-2h-1l-2-5H6L4 10H3a2 2 0 00-2 2v3a2 2 0 002 2h2"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-3xl font-black text-stone-950 leading-none">{{ $summary['ritase'] }}</p>
                <p class="mt-2 text-xs text-stone-400">Ritase bulan ini</p>
            </div>

            <div class="stat-card border border-stone-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-stone-500">Total Tonnase</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100">
                        <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-3xl font-black text-stone-950 leading-none">{{ number_format($summary['tonase'], 2, ',', '.') }}</p>
                <p class="mt-2 text-xs text-stone-400">Ton diangkut</p>
            </div>

            <div class="stat-card border border-emerald-100 bg-[#f0faf4] shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#164A41]">Saku + Terpal</p>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#e8f5e0]">
                        <svg class="h-4 w-4 text-[#164A41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-2xl font-black text-[#164A41] leading-none">{{ $rupiah($summary['sangu_supir'] + $summary['terpal']) }}</p>
                <p class="mt-2 text-xs text-[#164A41]/80">Total biaya supir & terpal</p>
            </div>
        </section>

        {{-- ═══ Filter ═════════════════════════════════════════════════ --}}
        <section class="card">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex items-center gap-3 mr-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-stone-100">
                        <svg class="h-4 w-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-stone-700 text-sm">Filter Periode</span>
                </div>
                <div>
                    <label for="bulan" class="label">Bulan</label>
                    <select id="bulan" name="bulan" class="field-white min-w-32">
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $namaBulan)
                            <option value="{{ $idx + 1 }}" @selected($bulan == $idx + 1)>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tahun" class="label">Tahun</label>
                    <input id="tahun" name="tahun" type="number" min="2020"
                        value="{{ $tahun }}" class="field-white w-28">
                </div>
                <button type="submit" class="btn-primary btn-compact">Terapkan</button>
            </form>
        </section>

        {{-- ═══ Data Table ══════════════════════════════════════════════ --}}
        <section class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kapal</th>
                            <th>Nopol</th>
                            <th class="text-right">Tonnase</th>
                            <th class="text-center">Trips</th>
                            <th>Rute</th>
                            <th>Pemilik</th>
                            <th class="text-right">Saku</th>
                            <th class="text-right">Terpal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $item)
                            <tr>
                                <td class="font-semibold text-stone-900 text-sm whitespace-nowrap">{{ $item->kapal->nama_kapal }}</td>
                                <td>
                                    <span class="badge-stone">{{ $item->kendaraan->nopol }}</span>
                                </td>
                                <td class="text-right text-stone-700 font-medium text-sm">{{ number_format((float) $item->tonase, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge-amber">{{ $item->ritase }}</span>
                                </td>
                                <td class="text-stone-600 text-sm">{{ $item->rute }}</td>
                                <td class="text-stone-600 text-sm">{{ $item->kendaraan->pemilik->nama_pemilik }}</td>
                                <td class="text-right text-stone-700 text-sm whitespace-nowrap">{{ $rupiah($item->sangu_supir) }}</td>
                                <td class="text-right text-stone-700 text-sm whitespace-nowrap">{{ $rupiah($item->terpal) }}</td>
                                <td>
                                    <div class="flex items-center gap-1.5">
                                        <button @click='openPreview(@json($item))' type="button"
                                           class="btn-icon bg-sky-50 text-sky-700 hover:bg-sky-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button @click='openEdit(@json($item))' type="button"
                                            class="btn-icon bg-stone-100 text-stone-600 hover:bg-amber-100 hover:text-amber-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('transaksi-operasional.destroy', $item) }}" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700"
                                                onclick="confirmHapus('{{ $item->kapal->nama_kapal }}', '{{ $item->kendaraan->nopol }}', document.getElementById('delete-form-{{ $item->id }}'))">
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
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7a8 3 0 0116 0M4 7v10a8 3 0 0016 0V7"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-stone-500">Belum ada data general tersimpan.</p>
                                        <p class="mt-1 text-xs text-stone-400">Tambahkan data melalui tombol di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transaksi->hasPages())
                <div class="mt-5 border-t border-stone-100 pt-5">
                    {{ $transaksi->links() }}
                </div>
            @endif
        </section>

        <!-- FORM MODAL (CREATE / EDIT) -->
        <div
            x-show="showFormModal"
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
                x-show="showFormModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white border border-stone-200 shadow-2xl p-6 sm:p-8"
                @click.outside="showFormModal = false"
            >
                <!-- Close Button -->
                <button
                    @click="showFormModal = false"
                    class="absolute top-4 right-4 text-stone-400 hover:text-stone-600 transition-colors"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="mb-6 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl"
                        :class="isEditMode ? 'bg-amber-50' : 'bg-[#e8f5e0]'"
                        style="width: 40px; height: 40px;">
                        <!-- Dynamic Icon -->
                        <svg class="h-6 w-6" :class="isEditMode ? 'text-amber-700' : 'text-emerald-800'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!isEditMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            <path x-show="isEditMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-black text-stone-900" style="font-family: 'Montserrat', sans-serif;"
                            x-text="isEditMode ? 'Ubah Data General' : 'Tambah Data General'"></h3>
                        <p class="text-xs text-stone-500"
                            x-text="isEditMode ? 'Perbarui data pengiriman operasional di sistem.' : 'Masukkan data pengiriman operasional baru ke sistem.'"></p>
                    </div>
                </div>

                <form id="form-transaksi" @submit.prevent="submitForm($event)" class="space-y-6">
                    @csrf
                    <!-- Dynamic method spoofing for Laravel PUT requests in Edit mode -->
                    <template x-if="isEditMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    @include('transaksi._form', ['transaksi' => null, 'isModal' => true])
                </form>
            </div>
        </div>

        <!-- DETAIL MODAL -->
        <div
            x-show="showPreviewModal"
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
                x-show="showPreviewModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white border border-stone-200 shadow-2xl p-6 sm:p-8"
                @click.outside="closePreview()"
            >
                <!-- Close Button -->
                <button
                    @click="closePreview()"
                    class="absolute top-4 right-4 text-stone-400 hover:text-stone-600 transition-colors"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <template x-if="previewItem">
                    <div>
                        <div class="mb-6 flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-700" style="width: 40px; height: 40px;">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-800" x-text="needsReload ? 'Ringkasan Pengisian Data' : 'Detail Transaksi'"></p>
                                <h3 class="text-lg font-black text-stone-900" style="font-family: 'Montserrat', sans-serif;">
                                    Data Tanggal <span x-text="formatDate(previewItem.tanggal)"></span>
                                </h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                            {{-- Kolom Kiri --}}
                            <div class="space-y-6">
                                {{-- Informasi Utama --}}
                                <div>
                                    <div class="mb-2.5 border-b border-stone-100 pb-1.5">
                                        <p class="font-bold text-stone-900">Informasi Utama</p>
                                    </div>
                                    <table class="w-full text-left text-xs">
                                        <tbody>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Tanggal Kegiatan</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatDate(previewItem.tanggal_kegiatan || previewItem.tanggal)"></td>
                                            </tr>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Kapal</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="previewItem.kapal?.nama_kapal || '-'"></td>
                                            </tr>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Kendaraan</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 align-top">
                                                    <span class="font-bold text-stone-850" x-text="previewItem.kendaraan?.nopol || '-'"></span>
                                                    <span class="text-xs text-stone-500 block mt-0.5" x-text="previewItem.kendaraan?.pemilik?.nama_pemilik ? '(' + previewItem.kendaraan.pemilik.nama_pemilik + ')' : ''"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Rute</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="previewItem.rute || '-'"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Muatan dan Biaya Dasar --}}
                                <div>
                                    <div class="mb-2.5 border-b border-stone-100 pb-1.5">
                                        <p class="font-bold text-stone-900">Muatan & Biaya Dasar</p>
                                    </div>
                                    <table class="w-full text-left text-xs">
                                        <tbody>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Ritase & Tonase</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-stone-850 align-top">
                                                    <span x-text="previewItem.ritase" class="font-bold"></span> ritase &middot; <span x-text="formatDecimal(previewItem.tonase)" class="font-bold"></span> ton
                                                </td>
                                            </tr>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Uang Saku Supir</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatRupiah(previewItem.sangu_supir)"></td>
                                            </tr>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Uang Terpal</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatRupiah(previewItem.terpal)"></td>
                                            </tr>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Biaya Operasional</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatRupiah(previewItem.operasional)"></td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Total Lapangan</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-bold text-stone-900 align-top" x-text="formatRupiah(previewItem.total_lapangan)"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="space-y-6">
                                {{-- Ringkasan Keuangan --}}
                                <div>
                                    <div class="mb-2.5 border-b border-stone-100 pb-1.5">
                                        <p class="font-bold text-stone-900">Ringkasan Keuangan</p>
                                    </div>
                                    <table class="w-full text-left text-xs">
                                        <tbody>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Pendapatan Kotor</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-bold text-emerald-600 align-top" x-text="formatRupiah(previewItem.pendapatan)"></td>
                                            </tr>
                                            <tr class="border-b border-stone-50">
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Total Biaya</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-semibold text-rose-600 align-top" x-text="formatRupiah(previewItem.total_biaya)"></td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Laba Bersih</td>
                                                <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                <td class="py-2 pl-2 font-black text-base align-top"
                                                    :class="Number(previewItem.laba_kotor) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                                                    x-text="formatRupiah(previewItem.laba_kotor)">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Gaji Telly --}}
                                <div>
                                    <div class="mb-2.5 border-b border-stone-100 pb-1.5">
                                        <p class="font-bold text-stone-900">Gaji Telly</p>
                                    </div>
                                    <template x-if="previewItem.gaji_telly">
                                        <table class="w-full text-left text-xs">
                                            <tbody>
                                                <tr class="border-b border-stone-50">
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Karyawan</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="previewItem.gaji_telly.karyawan?.nama || '-'"></td>
                                                </tr>
                                                <tr class="border-b border-stone-50">
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Gaji Satuan</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatRupiah(previewItem.gaji_telly.gaji) + ' / ton'"></td>
                                                </tr>
                                                <tr class="border-b border-stone-50">
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Total Kotor</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatRupiah(previewItem.gaji_telly.gaji_total)"></td>
                                                </tr>
                                                <tr class="border-b border-stone-50">
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Potongan PPh</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-semibold text-rose-600 align-top" x-text="formatRupiah(previewItem.gaji_telly.pph)"></td>
                                                </tr>
                                                <tr>
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Gaji Bersih</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-bold text-stone-900 align-top" x-text="formatRupiah(previewItem.gaji_telly.gaji_bersih)"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </template>
                                    <template x-if="!previewItem.gaji_telly">
                                        <p class="text-xs text-stone-400 italic">Tidak menggunakan Telly (opsional).</p>
                                    </template>
                                </div>

                                {{-- Paguyuban --}}
                                <div>
                                    <div class="mb-2.5 border-b border-stone-100 pb-1.5">
                                        <p class="font-bold text-stone-900">Paguyuban</p>
                                    </div>
                                    <template x-if="previewItem.paguyuban">
                                        <table class="w-full text-left text-xs">
                                            <tbody>
                                                <tr class="border-b border-stone-50">
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Tanggal Bayar</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatDate(previewItem.paguyuban.tanggal)"></td>
                                                </tr>
                                                <tr class="border-b border-stone-50">
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Tonase</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatDecimal(previewItem.tonase) + ' ton'"></td>
                                                </tr>
                                                <tr class="border-b border-stone-50">
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Tarif per Ton</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-semibold text-stone-850 align-top" x-text="formatRupiah(previewItem.paguyuban.tarif)"></td>
                                                </tr>
                                                <tr>
                                                    <td class="py-2 pr-3 font-semibold text-stone-500 w-[120px] align-top">Total Bayar</td>
                                                    <td class="py-2 px-1 font-semibold text-stone-400 w-[1%] align-top">:</td>
                                                    <td class="py-2 pl-2 font-bold text-stone-900 align-top" x-text="formatRupiah(previewItem.paguyuban.total_bayar)"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </template>
                                    <template x-if="!previewItem.paguyuban">
                                        <p class="text-xs text-stone-400 italic">Belum ada data paguyuban.</p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mt-4 pt-3 border-t border-stone-100">
                            <p class="font-bold text-stone-900 mb-1 flex items-center gap-1.5 text-xs">
                                <svg class="h-3.5 w-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                Keterangan Tambahan
                            </p>
                            <p class="text-stone-700 text-xs whitespace-pre-line bg-slate-50 p-2.5 rounded-xl border border-slate-100 mt-1" x-text="previewItem.keterangan || 'Tidak ada keterangan tambahan.'"></p>
                        </div>

                        <div class="mt-6 pt-3 border-t border-stone-100 flex items-center justify-end gap-3">
                            <button type="button" @click="closePreview()" :class="needsReload ? 'btn-success' : 'btn-soft'">
                                <span x-text="needsReload ? 'Selesai' : 'Tutup'"></span>
                            </button>
                            <button type="button" @click="showPreviewModal = false; openEdit(previewItem)" class="btn-primary">
                                Edit Data
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
<script>
function confirmHapus(namaKapal, nopol, formEl) {
    Swal.fire({
        title: 'Hapus Data General?',
        html: `<div style="text-align:left; font-size:13px; color:#57534e; line-height:1.6;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                <span style="font-weight:600; color:#292524;">Kapal</span>
                <span style="color:#a8a29e;">—</span>
                <span>${namaKapal}</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <span style="font-weight:600; color:#292524;">Nopol</span>
                <span style="color:#a8a29e;">—</span>
                <span>${nopol}</span>
            </div>
            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:10px 12px; font-size:12px; color:#be123c;">
                ⚠️ Data ini akan dihapus <strong>permanen</strong> dan tidak dapat dipulihkan.
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
