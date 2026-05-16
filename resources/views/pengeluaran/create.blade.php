<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Input Keuangan</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Tambah pengeluaran baru</h2>
        </div>
    </x-slot>

    <section class="card max-w-4xl">
        <form method="POST" action="{{ route('pengeluaran.store') }}" class="space-y-6">
            @csrf
            @include('pengeluaran._form')

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Simpan pengeluaran</button>
                <a href="{{ route('pengeluaran.index') }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
