<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('transaksi-operasional.show', $transaksi) }}"
               class="btn-icon border border-stone-200 bg-white text-stone-500 hover:bg-stone-100 hover:text-stone-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="page-label">Database General</p>
                <h2 class="page-title">Ubah Data General</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('transaksi-operasional.update', $transaksi) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="card p-6">
                @include('transaksi._form', ['transaksi' => $transaksi, 'isModal' => false])
            </div>
        </form>
    </div>
</x-app-layout>
