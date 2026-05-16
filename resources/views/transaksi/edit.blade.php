<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">Database General</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-stone-950">Ubah data general</h2>
        </div>
    </x-slot>

    <section class="card">
        <form method="POST" action="{{ route('transaksi-operasional.update', $transaksi) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('transaksi._form')

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Simpan perubahan</button>
                <a href="{{ route('transaksi-operasional.show', $transaksi) }}" class="btn-soft">Kembali</a>
            </div>
        </form>
    </section>
</x-app-layout>
