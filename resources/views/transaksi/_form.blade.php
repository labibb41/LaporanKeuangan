@php($transaksi  = $transaksi ?? null)
@php($isModal    = $isModal   ?? false)
@php($saveLabel  = $transaksi ? 'Simpan Perubahan' : 'Simpan Data')

<script>
    if (typeof window.transaksiForm === 'undefined') {
        window.transaksiForm = function(isModal, data) {
            return {
                isModal,
                isEdit: false,
                editId: null,

                tanggal:         data.tanggal,
                tanggal_kegiatan:data.tanggal_kegiatan,
                kapal_id:        data.kapal_id,
                kendaraan_id:    data.kendaraan_id,
                rute:            data.rute,
                ritase:          data.ritase,
                tonase:          data.tonase,
                saku:            data.saku,
                terpal:          data.terpal,
                pendapatan:      data.pendapatan,
                telly_id:        data.telly_id,
                gaji:            data.gaji,
                keterangan:      data.keterangan,
                version:         data.version || 1,

                sakuDisplay:     '',
                terpalDisplay:   '',
                gajiDisplay:     '',
                pendapatanDisplay:'',
                gajiTotalDisplay:'Rp 0',
                pphDisplay:      'Rp 0',
                gajiBersihDisplay:'Rp 0',
                pphPersen:       data.pphPersen,
                namaPemilik:     data.namaPemilik,

                init() {
                    this.sakuDisplay      = this.fmt(this.saku);
                    this.terpalDisplay    = this.fmt(this.terpal);
                    this.gajiDisplay      = this.fmt(this.gaji);
                    this.pendapatanDisplay= this.fmt(this.pendapatan);
                    this.hitung();
                },
                fmt(v) {
                    return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(Number(v||0));
                },
                parse(v) {
                    const s = String(v??'').replace(/[^0-9]/g,'');
                    return s==='' ? 0 : Number(s);
                },
                updateCurrency(field, e) {
                    const n = this.parse(e.target.value);
                    this[field] = n;
                    e.target.value = this.fmt(n);
                },
                onKapalChange(e) {
                    const tarif = Number(e.target.selectedOptions[0]?.dataset?.tarifTonase || 0);
                    if (!this.gaji || Number(this.gaji)===0) {
                        this.gaji = tarif;
                        this.gajiDisplay = this.fmt(tarif);
                    }
                    this.hitung();
                },
                onTellyChange(e) {
                    this.pphPersen = Number(e.target.selectedOptions[0]?.dataset?.pphPersen || 0);
                    this.hitung();
                },
                fetchPemilik(e) {
                    const url = e.target.selectedOptions[0]?.dataset?.infoUrl;
                    if (!url) { this.namaPemilik=''; return; }
                    fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}})
                        .then(r => r.json())
                        .then(d => { this.namaPemilik = d.nama_pemilik||''; })
                        .catch(() => { this.namaPemilik=''; });
                },
                hitung() {
                    const tot = Number(this.gaji||0) * Number(this.tonase||0);
                    const pph = tot * (Number(this.pphPersen||0)/100);
                    this.gajiTotalDisplay  = this.fmt(tot);
                    this.pphDisplay        = this.fmt(pph);
                    this.gajiBersihDisplay = this.fmt(Math.max(0, tot-pph));
                },
                populateForm(item) {
                    if (!item) {
                        this.isEdit=false; this.editId=null;
                        this.tanggal=new Date().toISOString().substring(0,10);
                        this.tanggal_kegiatan=''; this.kapal_id=''; this.kendaraan_id='';
                        this.rute=''; this.ritase=1; this.tonase=0;
                        this.saku=0; this.terpal=0; this.pendapatan=0;
                        this.telly_id=''; this.gaji=0; this.keterangan=''; this.namaPemilik='';
                        this.version = 1;
                    } else {
                        this.isEdit=true; this.editId=item.id;
                        this.tanggal           = item.tanggal          ? item.tanggal.substring(0,10) : '';
                        this.tanggal_kegiatan  = item.tanggal_kegiatan ? item.tanggal_kegiatan.substring(0,10) : '';
                        this.kapal_id          = item.kapal_id;
                        this.kendaraan_id      = item.kendaraan_id;
                        this.rute              = item.rute;
                        this.ritase            = item.ritase;
                        this.tonase            = Number(item.tonase||0);
                        this.saku              = Number(item.sangu_supir||0);
                        this.terpal            = Number(item.terpal||0);
                        this.pendapatan        = Number(item.pendapatan||0);
                        this.telly_id          = item.telly_id||'';
                        this.gaji              = item.gaji_telly ? Number(item.gaji_telly.gaji||0) : 0;
                        this.keterangan        = item.keterangan||'';
                        this.namaPemilik       = item.kendaraan?.pemilik?.nama_pemilik||'';
                        this.version           = item.version || 1;
                    }
                    this.sakuDisplay      = this.fmt(this.saku);
                    this.terpalDisplay    = this.fmt(this.terpal);
                    this.gajiDisplay      = this.fmt(this.gaji);
                    this.pendapatanDisplay= this.fmt(this.pendapatan);
                    this.hitung();
                }
            };
        };
    }

    window.transaksiInitialData = {!! json_encode([
        'tanggal'          => old('tanggal',          $transaksi?->tanggal?->format('Y-m-d')           ?? now()->format('Y-m-d')),
        'tanggal_kegiatan' => old('tanggal_kegiatan', $transaksi?->tanggal_kegiatan?->format('Y-m-d')  ?? ''),
        'kapal_id'         => old('kapal_id',         $transaksi?->kapal_id                            ?? ''),
        'kendaraan_id'     => old('kendaraan_id',     $transaksi?->kendaraan_id                        ?? ''),
        'rute'             => old('rute',              $transaksi?->rute                                ?? ''),
        'ritase'           => (int)   old('ritase',   $transaksi?->ritase                              ?? 1),
        'tonase'           => (float) old('tonase',   $transaksi?->tonase                              ?? 0),
        'saku'             => (int)   round((float) old('sangu_supir', $transaksi?->sangu_supir         ?? 0)),
        'terpal'           => (int)   round((float) old('terpal',      $transaksi?->terpal              ?? 0)),
        'pendapatan'       => (int)   round((float) old('pendapatan',  $transaksi?->pendapatan          ?? 0)),
        'telly_id'         => old('telly_id',         $transaksi?->telly_id                            ?? ''),
        'gaji'             => (float) old('gaji',     $transaksi?->gajiTelly?->gaji                    ?? 0),
        'keterangan'       => old('keterangan',       $transaksi?->keterangan                          ?? ''),
        'pphPersen'        => (float) old('pph_persen_preview', $transaksi?->gajiTelly?->karyawan?->pph_persen ?? 0),
        'namaPemilik'      => $transaksi?->kendaraan?->pemilik?->nama_pemilik                          ?? '',
        'version'          => (int)   ($transaksi?->version ?? 1),
    ]) !!};
</script>

{{-- ═══ ROOT ALPINE COMPONENT ═════════════════════════════════════════════ --}}
<div
    x-data="transaksiForm({{ $isModal ? 'true' : 'false' }}, window.transaksiInitialData)"
    x-on:set-form-data.window="populateForm($event.detail)"
>
    <input type="hidden" name="version" :value="version">
    {{-- TRUE 2-COLUMN LAYOUT ──────────────────────────────────────────────
         Left  (7/12): Informasi pengiriman + Muatan & Biaya
         Right (5/12): Gaji Telly + Catatan + Tombol Aksi
    ─────────────────────────────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns: 7fr 5fr; gap: 1.5rem; align-items: stretch;">

        {{-- ─── KOLOM KIRI ────────────────────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:0.75rem;">

            {{-- Tanggal Input + Tanggal Kegiatan --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label for="tanggal" class="label">Tanggal Input <span class="text-rose-500">*</span></label>
                    <input id="tanggal" name="tanggal" type="date" x-model="tanggal" class="field-white" required>
                    <p x-show="$parent?.errors?.tanggal" x-text="$parent?.errors?.tanggal?.[0]"
                       class="mt-1 text-xs font-semibold text-rose-600" style="display:none;"></p>
                </div>
                <div>
                    <label for="tanggal_kegiatan" class="label">Tanggal Kegiatan</label>
                    <input id="tanggal_kegiatan" name="tanggal_kegiatan" type="date" x-model="tanggal_kegiatan" class="field-white">
                </div>
            </div>

            {{-- Kapal + Nopol Kendaraan --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label for="kapal_id" class="label">Kapal <span class="text-rose-500">*</span></label>
                    <select id="kapal_id" name="kapal_id" x-model="kapal_id"
                            x-on:change="onKapalChange($event)" class="field-white" required>
                        <option value="">Pilih kapal</option>
                        @foreach ($daftarKapal as $kapal)
                            <option value="{{ $kapal->id }}" data-tarif-tonase="{{ (float) $kapal->tarif_tonase }}">
                                {{ $kapal->nama_kapal }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="$parent?.errors?.kapal_id" x-text="$parent?.errors?.kapal_id?.[0]"
                       class="mt-1 text-xs font-semibold text-rose-600" style="display:none;"></p>
                </div>
                <div>
                    <label for="kendaraan_id" class="label">Nopol Kendaraan <span class="text-rose-500">*</span></label>
                    <select id="kendaraan_id" name="kendaraan_id" x-model="kendaraan_id"
                            x-on:change="fetchPemilik($event)" class="field-white" required>
                        <option value="">Pilih nopol</option>
                        @foreach ($daftarKendaraan as $kendaraan)
                            <option value="{{ $kendaraan->id }}" data-info-url="{{ route('kendaraan.info', $kendaraan) }}">
                                {{ $kendaraan->nopol }} – {{ $kendaraan->pemilik->nama_pemilik }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="$parent?.errors?.kendaraan_id" x-text="$parent?.errors?.kendaraan_id?.[0]"
                       class="mt-1 text-xs font-semibold text-rose-600" style="display:none;"></p>
                    {{-- Nama Pemilik badge --}}
                    <div x-show="namaPemilik" style="display:none;"
                         class="mt-1.5 inline-flex items-center gap-1.5 rounded-lg border border-emerald-100 bg-[#f0faf4] px-2 py-0.5 text-[11px] font-semibold text-emerald-800">
                        <svg class="h-3 w-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Pemilik: <span x-text="namaPemilik" class="font-bold text-emerald-900"></span>
                    </div>
                </div>
            </div>

            {{-- Rute Pengiriman --}}
            <div>
                <label for="rute" class="label">Rute Pengiriman <span class="text-rose-500">*</span></label>
                <input id="rute" name="rute" type="text" x-model="rute" class="field-white"
                       placeholder="Contoh: Pelabuhan A → Gudang B" required>
                <p x-show="$parent?.errors?.rute" x-text="$parent?.errors?.rute?.[0]"
                   class="mt-1 text-xs font-semibold text-rose-600" style="display:none;"></p>
            </div>

            {{-- Separator --}}
            <div style="border-top: 1px solid #f1f5f9; padding-top: 0.25rem;">
                <p style="font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#94a3b8;">
                    Muatan &amp; Biaya
                </p>
            </div>

            {{-- Ritase + Tonase --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label for="ritase" class="label">Trips (Ritase) <span class="text-rose-500">*</span></label>
                    <input id="ritase" name="ritase" type="number" min="1"
                           x-model.number="ritase" class="field-white" required>
                    <p x-show="$parent?.errors?.ritase" x-text="$parent?.errors?.ritase?.[0]"
                       class="mt-1 text-xs font-semibold text-rose-600" style="display:none;"></p>
                </div>
                <div>
                    <label for="tonase" class="label">Tonase (Ton) <span class="text-rose-500">*</span></label>
                    <input id="tonase" name="tonase" type="number" min="0" step="0.01"
                           x-model.number="tonase" x-on:input.debounce.150ms="hitung()"
                           class="field-white" required>
                    <p x-show="$parent?.errors?.tonase" x-text="$parent?.errors?.tonase?.[0]"
                       class="mt-1 text-xs font-semibold text-rose-600" style="display:none;"></p>
                </div>
            </div>

            {{-- Uang Saku + Uang Terpal --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label class="label">Uang Saku Supir</label>
                    <input name="sangu_supir" type="hidden" x-bind:value="saku">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400 text-xs font-bold">Rp</span>
                        <input type="text" x-model="sakuDisplay"
                               x-on:input="updateCurrency('saku', $event)"
                               x-on:blur="sakuDisplay = fmt(saku)"
                               inputmode="numeric" class="field-white pl-9" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="label">Uang Terpal</label>
                    <input name="terpal" type="hidden" x-bind:value="terpal">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400 text-xs font-bold">Rp</span>
                        <input type="text" x-model="terpalDisplay"
                               x-on:input="updateCurrency('terpal', $event)"
                               x-on:blur="terpalDisplay = fmt(terpal)"
                               inputmode="numeric" class="field-white pl-9" placeholder="0">
                    </div>
                </div>
            </div>

            {{-- Pendapatan Bruto --}}
            <div>
                <label for="pendapatan_display" class="label">Pendapatan Bruto (Kotor)</label>
                <input name="pendapatan" type="hidden" x-bind:value="pendapatan">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400 text-xs font-bold">Rp</span>
                    <input id="pendapatan_display" type="text" x-model="pendapatanDisplay"
                           x-on:input="updateCurrency('pendapatan', $event)"
                           x-on:blur="pendapatanDisplay = fmt(pendapatan)"
                           inputmode="numeric" class="field-white pl-9 font-semibold" placeholder="0">
                </div>
                <p class="mt-1 text-[11px] text-slate-400">Isi agar laba bersih tidak negatif.</p>
                <p x-show="$parent?.errors?.pendapatan" x-text="$parent?.errors?.pendapatan?.[0]"
                   class="mt-1 text-xs font-semibold text-rose-600" style="display:none;"></p>
            </div>

        </div>{{-- /kolom kiri --}}

        {{-- ─── KOLOM KANAN ───────────────────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:0.75rem;">

            {{-- Gaji Telly Box --}}
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:0.75rem; padding:0.875rem; display:flex; flex-direction:column; gap:0.625rem;">
                <p style="font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#94a3b8; border-bottom:1px solid #e2e8f0; padding-bottom:0.375rem;">
                    Gaji Telly <span style="font-weight:400; text-transform:none;">(opsional)</span>
                </p>

                {{-- Karyawan --}}
                <div>
                    <label for="telly_id" class="label">Karyawan (Telly)</label>
                    <select id="telly_id" name="telly_id" x-model="telly_id"
                            x-on:change="onTellyChange($event)" class="field-white">
                        <option value="">Tidak ada</option>
                        @foreach ($daftarKaryawan as $karyawan)
                            <option value="{{ $karyawan->id }}" data-pph-persen="{{ (float) $karyawan->pph_persen }}">
                                {{ $karyawan->nama }}{{ $karyawan->jabatan ? ' – '.$karyawan->jabatan : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tarif + Estimasi --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                    <div>
                        <label for="gaji" class="label">Tarif (Rp/ton)</label>
                        <input name="gaji" type="hidden" x-bind:value="gaji">
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400 text-xs font-bold">Rp</span>
                            <input id="gaji" type="text" x-model="gajiDisplay"
                                   x-on:input="updateCurrency('gaji', $event); hitung()"
                                   x-on:blur="gajiDisplay = fmt(gaji)"
                                   inputmode="numeric" class="field-white pl-9" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="label">Estimasi Bersih</label>
                        <div style="border:1px solid #d1fae5; background:#f0fdf4; border-radius:0.625rem; padding:0.5rem 0.75rem;">
                            <div style="display:flex; justify-content:space-between; font-size:11px; color:#64748b; margin-bottom:2px;">
                                <span>Kotor</span><span x-text="gajiTotalDisplay" style="font-weight:600; color:#334155;"></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:11px; color:#ef4444; margin-bottom:2px;">
                                <span>PPh</span><span x-text="pphDisplay" style="font-weight:600;"></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:11px; border-top:1px solid #bbf7d0; padding-top:4px; font-weight:700; color:#065f46;">
                                <span>Bersih</span><span x-text="gajiBersihDisplay"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan Tambahan --}}
            <div>
                <label for="keterangan" class="label">Catatan Tambahan</label>
                <textarea id="keterangan" name="keterangan" rows="3"
                          x-model="keterangan" class="field-white resize-none w-full"
                          placeholder="Tambahkan catatan khusus jika ada..."></textarea>
            </div>

            {{-- SPACER – dorong tombol ke bawah --}}
            <div style="flex:1;"></div>

            {{-- ─── FOOTER ACTIONS ──────────────────────────────────── --}}
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.75rem; border-top:1px solid #e2e8f0;">

                {{-- Batal / Kembali --}}
                <template x-if="isModal">
                    <button type="button" x-on:click="$dispatch('close-form-modal')" class="btn-soft">
                        Batal
                    </button>
                </template>
                <template x-if="!isModal">
                    <a href="{{ route('transaksi-operasional.index') }}" class="btn-soft">Kembali</a>
                </template>

                {{-- SIMPAN – teks dari PHP, tidak bergantung Alpine sama sekali --}}
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <svg x-show="$parent?.isSubmitting" style="display:none;"
                         class="animate-spin h-4 w-4 text-white shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    {{ $saveLabel }}
                </button>

            </div>

        </div>{{-- /kolom kanan --}}

    </div>{{-- /grid utama --}}
</div>
