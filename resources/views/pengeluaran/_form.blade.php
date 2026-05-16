@php
    $pengeluaran = $pengeluaran ?? null;
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="tanggal" class="mb-2 block text-sm font-semibold text-stone-700">Tanggal</label>
    <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $pengeluaran?->tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="field py-2" required>
    </div>
    <div>
        <label for="jenis" class="mb-2 block text-sm font-semibold text-stone-700">Jenis pengeluaran</label>
    <input id="jenis" name="jenis" type="text" value="{{ old('jenis', $pengeluaran?->jenis) }}" class="field py-2" required>
    </div>
    <div>
        <label for="nama_kegiatan" class="mb-2 block text-sm font-semibold text-stone-700">Nama pengeluaran</label>
    <input id="nama_kegiatan" name="nama_kegiatan" type="text" value="{{ old('nama_kegiatan', $pengeluaran?->nama_kegiatan) }}" class="field py-2">
    </div>
    <div>
        <label for="penerima" class="mb-2 block text-sm font-semibold text-stone-700">Penerima</label>
    <input id="penerima" name="penerima" type="text" value="{{ old('penerima', $pengeluaran?->penerima) }}" class="field py-2">
    </div>
    <div class="md:col-span-2">
        <label for="jumlah" class="mb-2 block text-sm font-semibold text-stone-700">Jumlah</label>
    <input id="jumlah" name="jumlah" type="number" min="0" step="0.01" value="{{ old('jumlah', $pengeluaran?->jumlah) }}" class="field py-2" required>
    </div>
    <div class="md:col-span-2">
        <label for="keterangan" class="mb-2 block text-sm font-semibold text-stone-700">Keterangan</label>
    <textarea id="keterangan" name="keterangan" rows="4" class="field py-2">{{ old('keterangan', $pengeluaran?->keterangan) }}</textarea>
    </div>
</div>
