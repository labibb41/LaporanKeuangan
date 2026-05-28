<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Edit Master</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Ubah data kendaraan</h2>
        </div>
    </x-slot>

    <section class="card max-w-4xl">
        <form method="POST" action="{{ route('kendaraan.update', $kendaraan) }}" class="grid gap-5 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label for="nopol" class="label">Nomor polisi</label>
                <input id="nopol" name="nopol" type="text" value="{{ old('nopol', $kendaraan->nopol) }}" class="field" required>
            </div>
            <div>
                <label for="pemilik_id" class="label">Pemilik</label>
                <select id="pemilik_id" name="pemilik_id" class="field" required>
                    <option value="" disabled>Pilih pemilik...</option>
                    @foreach ($daftarPemilik as $pemilik)
                        <option value="{{ $pemilik->id }}" @selected(old('pemilik_id', $kendaraan->pemilik_id) == $pemilik->id)>{{ $pemilik->nama_pemilik }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap gap-3 md:col-span-2">
                <button type="submit" class="btn-primary">Simpan perubahan</button>
                <a href="{{ route('kendaraan.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
