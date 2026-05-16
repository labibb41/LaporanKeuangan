@php($transaksi = $transaksi ?? null)

<div
    class="space-y-6"
    x-data="{
        saku: {{ (int) round((float) old('sangu_supir', $transaksi?->sangu_supir ?? 0)) }},
        terpal: {{ (int) round((float) old('terpal', $transaksi?->terpal ?? 0)) }},
        tonase: {{ (float) old('tonase', $transaksi?->tonase ?? 0) }},
        gaji: {{ (float) old('gaji', $transaksi?->gajiTelly?->gaji ?? 0) }},
        pphPersen: {{ (float) old('pph_persen_preview', $transaksi?->gajiTelly?->karyawan?->pph_persen ?? 0) }},
        namaPemilik: '{{ $transaksi?->kendaraan?->pemilik?->nama_pemilik ?? '' }}',
        sakuDisplay: '',
        terpalDisplay: '',
        gajiDisplay: '',
        gajiTotalDisplay: '',
        pphDisplay: '',
        gajiBersihDisplay: '',
        init() {
            this.sakuDisplay = this.formatRupiah(this.saku);
            this.terpalDisplay = this.formatRupiah(this.terpal);
            this.gajiDisplay = this.formatRupiah(this.gaji);
            this.recalculateGaji();
        },
        formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(Number(value || 0));
        },
        parseCurrency(value) {
            const angka = String(value ?? '').replace(/[^0-9]/g, '');
            return angka === '' ? 0 : Number(angka);
        },
        updateCurrency(field, event) {
            const angka = this.parseCurrency(event.target.value);
            this[field] = angka;
            event.target.value = this.formatRupiah(angka);
        },
        onKapalChange(event) {
            const tarif = Number(event?.target?.selectedOptions?.[0]?.dataset?.tarifTonase || 0);
            if (!this.gaji || Number(this.gaji) === 0) {
                this.gaji = tarif;
                this.gajiDisplay = this.formatRupiah(this.gaji);
            }
            this.recalculateGaji();
        },
        onTellyChange(event) {
            this.pphPersen = Number(event?.target?.selectedOptions?.[0]?.dataset?.pphPersen || 0);
            this.recalculateGaji();
        },
        fetchPemilik(event) {
            const url = event?.target?.selectedOptions?.[0]?.dataset?.infoUrl;
            if (!url) { this.namaPemilik = ''; return; }
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => { this.namaPemilik = data.nama_pemilik || ''; })
                .catch(() => { this.namaPemilik = ''; });
        },
        recalculateGaji() {
            const gajiTotal = Number(this.gaji || 0) * Number(this.tonase || 0);
            const pph = gajiTotal * (Number(this.pphPersen || 0) / 100);
            const bersih = Math.max(0, gajiTotal - pph);
            this.gajiTotalDisplay = this.formatRupiah(gajiTotal);
            this.pphDisplay = this.formatRupiah(pph);
            this.gajiBersihDisplay = this.formatRupiah(bersih);
        }
    }"
>
    <section class="card">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Database General</p>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label for="tanggal" class="label">Tanggal input</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $transaksi?->tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="field-white" required>
            </div>
            <div>
                <label for="kapal_id" class="label">Kapal</label>
                <select id="kapal_id" name="kapal_id" x-on:change="onKapalChange($event)" class="field-white" required>
                    <option value="">Pilih kapal</option>
                    @foreach ($daftarKapal as $kapal)
                        <option value="{{ $kapal->id }}" data-tarif-tonase="{{ (float) $kapal->tarif_tonase }}" @selected(old('kapal_id', $transaksi?->kapal_id) == $kapal->id)>{{ $kapal->nama_kapal }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="kendaraan_id" class="label">Nopol kendaraan</label>
                <select id="kendaraan_id" name="kendaraan_id"
                    x-on:change="fetchPemilik($event)"
                    class="field-white" required>
                    <option value="">Pilih nopol</option>
                    @foreach ($daftarKendaraan as $kendaraan)
                        <option value="{{ $kendaraan->id }}" data-info-url="{{ route('kendaraan.info', $kendaraan) }}" @selected(old('kendaraan_id', $transaksi?->kendaraan_id) == $kendaraan->id)>
                            {{ $kendaraan->nopol }} - {{ $kendaraan->pemilik->nama_pemilik }}
                        </option>
                    @endforeach
                </select>
                @error('kendaraan_id')
                    <p class="mt-2 rounded-xl bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700">⚠️ {{ $message }}</p>
                @enderror

                {{-- Autofill Nama Pemilik --}}
                <div x-show="namaPemilik" x-cloak class="mt-2 flex items-center gap-2 rounded-xl border border-sky-100 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-800">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Pemilik: <span x-text="namaPemilik" class="font-bold"></span></span>
                </div>
            </div>
            <div>
                <label for="rute" class="label">Rute</label>
                <input id="rute" name="rute" type="text" value="{{ old('rute', $transaksi?->rute) }}" class="field-white" required>
            </div>
        </div>
    </section>


    <section class="card-soft">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Muatan dan Biaya Dasar</p>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label for="ritase" class="label">Trips</label>
                <input id="ritase" name="ritase" type="number" min="1" value="{{ old('ritase', $transaksi?->ritase ?? 1) }}" class="field-white" required>
            </div>
            <div>
                <label for="tonase" class="label">Tonnase</label>
                <input id="tonase" name="tonase" type="number" min="0" step="0.01" x-model.number="tonase" x-on:input.debounce.150ms="recalculateGaji()" value="{{ old('tonase', $transaksi?->tonase ?? 0) }}" class="field-white" required>
            </div>
            <div>
                <label for="sangu_supir" class="label">Saku</label>
                <input name="sangu_supir" type="hidden" x-bind:value="saku">
                <input id="sangu_supir" type="text" x-model="sakuDisplay" x-on:input="updateCurrency('saku', $event)" x-on:blur="sakuDisplay = formatRupiah(saku)" inputmode="numeric" class="field-white">
            </div>
            <div>
                <label for="terpal" class="label">Terpal</label>
                <input name="terpal" type="hidden" x-bind:value="terpal">
                <input id="terpal" type="text" x-model="terpalDisplay" x-on:input="updateCurrency('terpal', $event)" x-on:blur="terpalDisplay = formatRupiah(terpal)" inputmode="numeric" class="field-white">
            </div>
            <div class="md:col-span-2">
                <label for="pendapatan" class="label">Pendapatan opsional</label>
                <input id="pendapatan" name="pendapatan" type="number" min="0" step="0.01" value="{{ old('pendapatan', $transaksi?->pendapatan ?? 0) }}" class="field-white">
                <p class="mt-2 text-xs text-stone-500">Boleh dikosongkan kalau fokusnya hanya database general dan operasional.</p>
            </div>

            <div class="md:col-span-2 mt-2 border-t border-stone-200/70 pt-4">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Gaji Telly (opsional)</p>
            </div>

            <div class="md:col-span-2">
                <label for="telly_id" class="label">Karyawan (Telly)</label>
                <select id="telly_id" name="telly_id" x-on:change="onTellyChange($event)" class="field-white">
                    <option value="">Tidak ada</option>
                    @foreach ($daftarKaryawan as $karyawan)
                        <option value="{{ $karyawan->id }}" data-pph-persen="{{ (float) $karyawan->pph_persen }}" @selected(old('telly_id', $transaksi?->telly_id) == $karyawan->id)>
                            {{ $karyawan->nama }}{{ $karyawan->jabatan ? ' - '.$karyawan->jabatan : '' }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-stone-500">Rumus: tonase x harga tonase (Rp/ton) dari master kapal.</p>
            </div>

            <div>
                <label for="gaji" class="label">Harga tonase (Rp / ton)</label>
                <input name="gaji" type="hidden" x-bind:value="gaji">
                <input id="gaji" type="text" x-model="gajiDisplay" x-on:input="updateCurrency('gaji', $event); recalculateGaji()" x-on:blur="gajiDisplay = formatRupiah(gaji)" inputmode="numeric" class="field-white">
            </div>

            <div class="rounded-[1.5rem] border border-sky-100 bg-sky-50/50 px-4 py-3">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Estimasi gaji</p>
                <div class="mt-2 grid gap-1 text-sm font-semibold text-stone-700">
                    <div class="flex items-center justify-between gap-3"><span>Total</span><span x-text="gajiTotalDisplay"></span></div>
                    <div class="flex items-center justify-between gap-3"><span>PPh</span><span x-text="pphDisplay"></span></div>
                    <div class="flex items-center justify-between gap-3"><span>Bersih</span><span x-text="gajiBersihDisplay"></span></div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="keterangan" class="label">Keterangan (opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" class="field-white">{{ old('keterangan', $transaksi?->keterangan) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="tanggal_kegiatan" class="label">Tanggal kegiatan (opsional)</label>
                <input id="tanggal_kegiatan" name="tanggal_kegiatan" type="date" value="{{ old('tanggal_kegiatan', $transaksi?->tanggal_kegiatan?->format('Y-m-d')) }}" class="field-white">
            </div>
        </div>
    </section>
</div>
