<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('transaksi-operasional.index') }}"
               class="btn-icon border border-stone-200 bg-white text-stone-500 hover:bg-stone-100 hover:text-stone-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="page-label">Database General</p>
                <h2 class="page-title">Tambah Data General</h2>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('transaksi-operasional.store') }}" class="space-y-6 max-w-4xl">
        @csrf
        @include('transaksi._form')

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Data General
            </button>
            <a href="{{ route('transaksi-operasional.index') }}" class="btn-soft">Kembali</a>
        </div>
    </form>
</x-app-layout>
