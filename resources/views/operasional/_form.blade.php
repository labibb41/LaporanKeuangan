@php
    $rekap = $rekap ?? null;
    $rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
@endphp

<div
    class="grid gap-6 lg:grid-cols-2"
    x-data="{
        sanguSupir: {{ (int) round((float) old('sangu_supir', $rekap?->sangu_supir ?? 0)) }},
        terpal: {{ (int) round((float) old('terpal', $rekap?->terpal ?? 0)) }},
        operasional: {{ (float) old('operasional', $rekap?->operasional ?? 0) }},
        sanguSupirDisplay: '',
        terpalDisplay: '',
        init() {
            this.sanguSupirDisplay = this.formatRupiah(this.sanguSupir);
            this.terpalDisplay = this.formatRupiah(this.terpal);
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
        get total() {
            return (Number(this.sanguSupir) || 0) + (Number(this.terpal) || 0) + (Number(this.operasional) || 0);
        }
    }"
>
    <input type="hidden" name="bulan" value="{{ old('bulan', $bulan) }}">
    <input type="hidden" name="tahun" value="{{ old('tahun', $tahun) }}">

    <section class="card-soft">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Rekap Operasional</p>
        <div class="mt-4 grid gap-4">
            <div>
                <label for="kapal_id" class="label">Kapal</label>
                <select id="kapal_id" name="kapal_id" class="field-white" required>
                    <option value="">Pilih kapal</option>
                    @foreach ($daftarKapal as $kapal)
                        <option value="{{ $kapal->id }}" @selected(old('kapal_id', $rekap?->kapal_id) == $kapal->id)>{{ $kapal->nama_kapal }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="rute" class="label">Rute</label>
                <input id="rute" name="rute" type="text" value="{{ old('rute', $rekap?->rute) }}" class="field-white">
            </div>
            <div>
                <label for="tanggal_kegiatan" class="label">Tgl kegiatan</label>
                <input id="tanggal_kegiatan" name="tanggal_kegiatan" type="date" value="{{ old('tanggal_kegiatan', $rekap?->tanggal_kegiatan?->format('Y-m-d')) }}" class="field-white">
            </div>
            <div>
                <label for="telly_id" class="label">Telly</label>
                <select id="telly_id" name="telly_id" class="field-white">
                    <option value="">Pilih telly</option>
                    @foreach ($daftarKaryawan as $karyawan)
                        <option value="{{ $karyawan->id }}" @selected(old('telly_id', $rekap?->telly_id) == $karyawan->id)>{{ $karyawan->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="keterangan" class="label">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4" class="field-white">{{ old('keterangan', $rekap?->keterangan) }}</textarea>
            </div>
        </div>
    </section>

    <section class="card-soft">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Nilai Rekap</p>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label for="trips" class="label">Trips</label>
                <input id="trips" name="trips" type="number" min="0" value="{{ old('trips', $rekap?->trips ?? 0) }}" class="field-white">
            </div>
            <div>
                <label for="tonase" class="label">Tonnase</label>
                <input id="tonase" name="tonase" type="number" min="0" step="0.01" value="{{ old('tonase', $rekap?->tonase ?? 0) }}" class="field-white">
            </div>
            <div>
                <label for="sangu_supir" class="label">Sangu supir</label>
                <input name="sangu_supir" type="hidden" x-bind:value="sanguSupir">
                <input id="sangu_supir" type="text" x-model="sanguSupirDisplay" x-on:input="updateCurrency('sanguSupir', $event)" x-on:blur="sanguSupirDisplay = formatRupiah(sanguSupir)" inputmode="numeric" class="field-white">
            </div>
            <div>
                <label for="terpal" class="label">Terpal</label>
                <input name="terpal" type="hidden" x-bind:value="terpal">
                <input id="terpal" type="text" x-model="terpalDisplay" x-on:input="updateCurrency('terpal', $event)" x-on:blur="terpalDisplay = formatRupiah(terpal)" inputmode="numeric" class="field-white">
            </div>
            <div>
                <label for="operasional" class="label">Operasional</label>
                <input id="operasional" name="operasional" type="number" min="0" step="0.01" x-model.number="operasional" value="{{ old('operasional', $rekap?->operasional ?? 0) }}" class="field-white">
            </div>
            <div>
                <label for="total_preview" class="label">Total</label>
                <input id="total_preview" type="text" x-bind:value="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(total)" class="w-full rounded-2xl border-stone-200 bg-stone-100 shadow-sm focus:border-stone-200 focus:ring-0" readonly>
            </div>
        </div>
    </section>
</div>
