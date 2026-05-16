<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-label">Master Data</p>
            <h2 class="page-title">Data Kapal Operasional</h2>
            <p class="page-sub">Kelola armada kapal beserta voyage, tarif, dan status operasional.</p>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[380px_1fr]">

        {{-- ── FORM TAMBAH ─────────────────────────────────────── --}}
        <div class="space-y-4">
            <section class="card">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-100">
                        <svg class="h-5 w-5 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-stone-950">Tambah Kapal</h3>
                        <p class="text-xs text-stone-400">Isi data kapal operasional baru</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('kapal.store') }}" class="space-y-4">
                    @csrf

                    {{-- Nama Kapal --}}
                    <div>
                        <label for="nama_kapal" class="label">Nama Kapal <span class="text-rose-500">*</span></label>
                        <input id="nama_kapal" name="nama_kapal" type="text"
                            value="{{ old('nama_kapal') }}"
                            placeholder="cth. KM Permata Jaya"
                            class="field" required>
                        @error('nama_kapal')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Kapasitas --}}
                        <div>
                            <label for="kapasitas_ton" class="label">Kapasitas (ton)</label>
                            <input id="kapasitas_ton" name="kapasitas_ton" type="number"
                                min="0" step="0.01"
                                value="{{ old('kapasitas_ton') }}"
                                placeholder="0"
                                class="field">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Tarif Tonase --}}
                        <div>
                            <label for="tarif_tonase" class="label">Tarif Telly (Rp/ton)</label>
                            <input id="tarif_tonase" name="tarif_tonase" type="number"
                                min="0" step="0.01"
                                value="{{ old('tarif_tonase', 0) }}"
                                class="field">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="label">Status</label>
                            <select id="status" name="status" class="field">
                                <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                                <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label for="keterangan" class="label">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="2"
                            placeholder="Catatan tambahan (opsional)"
                            class="field resize-none">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Kapal
                    </button>
                </form>
            </section>
        </div>

        {{-- ── DAFTAR KAPAL ─────────────────────────────────────── --}}
        <section class="card min-w-0">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="section-label">Daftar Kapal</p>
                    <h3 class="section-title text-base">Kapal yang tersedia</h3>
                </div>
                <span class="badge-stone text-sm px-4 py-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/>
                    </svg>
                    {{ $kapal->total() }} kapal
                </span>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kapal</th>
                            <th class="text-right">Kapasitas</th>
                            <th class="text-right">Tarif/ton</th>
                            <th>Status</th>
                            <th class="text-center">Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kapal as $item)
                            <tr>
                                <td>
                                    <p class="font-semibold text-stone-900">{{ $item->nama_kapal }}</p>
                                </td>
                                <td class="text-right text-stone-600 text-sm">
                                    @if($item->kapasitas_ton)
                                        {{ number_format((float) $item->kapasitas_ton, 0, ',', '.') }} ton
                                    @else
                                        <span class="text-stone-300">—</span>
                                    @endif
                                </td>
                                <td class="text-right font-semibold text-stone-700 text-sm whitespace-nowrap">
                                    Rp {{ number_format((float) $item->tarif_tonase, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if(($item->status ?? 'aktif') === 'aktif')
                                        <span class="badge-green">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge-stone">
                                            <span class="h-1.5 w-1.5 rounded-full bg-stone-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge-blue">{{ $item->transaksi_operasional_count }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('kapal.edit', $item) }}"
                                           class="btn-icon bg-stone-100 text-stone-600 hover:bg-sky-100 hover:text-sky-700 text-xs font-semibold">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('kapal.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn-icon bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700"
                                                onclick="return confirm('Hapus kapal {{ $item->nama_kapal }}?')">
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
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-stone-500">Belum ada data kapal.</p>
                                        <p class="mt-1 text-xs text-stone-400">Tambahkan kapal melalui form di sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kapal->hasPages())
                <div class="mt-5 border-t border-stone-100 pt-5">
                    {{ $kapal->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
