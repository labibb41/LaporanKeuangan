<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Edit Master</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Ubah data pemilik</h2>
        </div>
    </x-slot>

    <section class="card max-w-3xl">
        <form method="POST" action="{{ route('pemilik.update', $pemilik) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_pemilik" class="label">Nama pemilik</label>
                <input id="nama_pemilik" name="nama_pemilik" type="text" value="{{ old('nama_pemilik', $pemilik->nama_pemilik) }}" class="field" required>
                @error('nama_pemilik')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Simpan perubahan</button>
                <a href="{{ route('pemilik.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
