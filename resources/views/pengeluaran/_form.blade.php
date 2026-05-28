@php
    $pengeluaran = $pengeluaran ?? null;
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <!-- Tanggal -->
    <div>
        <label for="tanggal" class="label">Tanggal <span class="text-rose-500">*</span></label>
        <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $pengeluaran?->tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="field py-2" required>
    </div>

    <!-- Penerima -->
    <div>
        <label for="penerima" class="label">Penerima</label>
        <select id="penerima" name="penerima" class="field py-2">
            <option value="">-- Pilih karyawan / penerima --</option>
            @foreach ($daftarKaryawan as $karyawan)
                <option value="{{ $karyawan->nama }}" @selected(old('penerima', $pengeluaran?->penerima) == $karyawan->nama)>
                    {{ $karyawan->nama }} {{ $karyawan->jabatan ? '('.$karyawan->jabatan.')' : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Jenis Pengeluaran -->
    @php
        $existingJenis = old('jenis', $pengeluaran?->jenis);
        $isCustom = $existingJenis && !($daftarJenis ?? collect())->contains($existingJenis);
    @endphp
    <div x-data="{ 
            mode: '{{ $isCustom ? 'input' : 'select' }}',
            val: '{{ addslashes($existingJenis) }}'
         }">
        <label for="jenis" class="label">Jenis Pengeluaran <span class="text-rose-500">*</span></label>
        
        <div x-show="mode === 'select'">
            <select name="jenis" class="field py-2 w-full" x-model="val" :disabled="mode !== 'select'" required
                    @change="if($event.target.value === 'NEW') { mode = 'input'; val = ''; }">
                <option value="">-- Pilih jenis pengeluaran --</option>
                @foreach ($daftarJenis ?? [] as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                @endforeach
                <option value="NEW" class="font-bold text-blue-600">+ Tambah jenis pengeluaran baru</option>
            </select>
        </div>

        <div x-cloak x-show="mode === 'input'" class="flex gap-2">
            <input type="text" name="jenis" class="field py-2 w-full" placeholder="Ketik jenis pengeluaran..." x-model="val" :disabled="mode !== 'input'" required>
            <button type="button" @click="mode = 'select'; val = ''" class="btn-soft px-3 py-2 text-xs shrink-0 rounded-lg">Batal</button>
        </div>
    </div>

    <!-- Nama Pengeluaran -->
    <div>
        <label for="nama_kegiatan" class="label">Nama Pengeluaran</label>
        <input id="nama_kegiatan" name="nama_kegiatan" type="text" value="{{ old('nama_kegiatan', $pengeluaran?->nama_kegiatan) }}" class="field py-2" placeholder="Contoh: Pembelian Solar Armada">
    </div>

    <!-- Jumlah -->
    <div class="md:col-span-2">
        <label for="jumlah_rupiah" class="label">Jumlah (Rp) <span class="text-rose-500">*</span></label>
        <div class="relative rounded-lg shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-slate-400 text-xs font-bold">Rp</span>
            </div>
            <input id="jumlah_rupiah" type="text" class="field pl-9 w-full font-bold text-slate-800 py-2.5" placeholder="0" required 
                value="{{ old('jumlah', $pengeluaran?->jumlah) ? number_format(old('jumlah', $pengeluaran?->jumlah), 0, '', '.') : '' }}"
                oninput="this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); document.getElementById('jumlah').value = this.value.replace(/\./g, '');">
            <input type="hidden" id="jumlah" name="jumlah" value="{{ old('jumlah', $pengeluaran?->jumlah) }}">
        </div>
    </div>

    <!-- Keterangan -->
    <div class="md:col-span-2">
        <label for="keterangan" class="label">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3" class="field py-2 resize-none" placeholder="Masukkan rincian atau catatan pengeluaran di sini...">{{ old('keterangan', $pengeluaran?->keterangan) }}</textarea>
    </div>
</div>
