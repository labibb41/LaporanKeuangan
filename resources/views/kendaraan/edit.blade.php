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
                <label for="nama_pemilik" class="label">Pemilik</label>
                <input list="daftar-pemilik" id="nama_pemilik" name="nama_pemilik" type="text" value="{{ old('nama_pemilik', $kendaraan->pemilik->nama_pemilik ?? '') }}" class="field" placeholder="Ketik nama pemilik" required>
                <datalist id="daftar-pemilik">
                    @foreach ($daftarPemilik as $pemilik)
                        <option value="{{ $pemilik->nama_pemilik }}">
                    @endforeach
                </datalist>
            </div>

            <div class="flex flex-wrap gap-3 md:col-span-2">
                <button type="submit" class="btn-primary">Simpan perubahan</button>
                <a href="{{ route('kendaraan.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
