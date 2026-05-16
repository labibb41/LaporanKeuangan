<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Operasional</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Ubah rekap operasional</h2>
        </div>
    </x-slot>

    <section class="card">
        <form method="POST" action="{{ route('operasional.update', $rekap) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('operasional._form')

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Simpan perubahan</button>
                <a href="{{ route('operasional.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
