<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laporan Keuangan') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=1">
    </head>
    <body class="min-h-screen bg-slate-50 antialiased" style="font-family: 'Inter Tight', sans-serif; color: #1e293b;">
        <div class="flex min-h-screen items-center justify-center px-4 py-10"
             style="background: radial-gradient(circle at top left, rgba(22,74,65,0.10) 0%, transparent 30%), radial-gradient(circle at bottom right, rgba(241,178,74,0.08) 0%, transparent 35%), linear-gradient(180deg, #f8fafc 0%, #f0faf4 100%);">
            <div class="w-full max-w-6xl overflow-hidden rounded-[2.75rem] border border-stone-200 bg-white shadow-2xl shadow-slate-200/60">
                <div class="grid lg:grid-cols-[0.95fr_0.75fr]">
                {{-- Left panel: Forest Green --}}
                <div class="hidden p-10 text-white lg:block"
                     style="background: linear-gradient(135deg, #164A41 0%, #0f3830 60%, #061f1a 100%);">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 shadow-sm overflow-hidden"
                              style="width: 56px; height: 56px; min-width: 56px; min-height: 56px; background: rgba(241,178,74,0.15);">
                            <img src="{{ asset('logo.png') }}" class="h-10 w-10 object-contain" alt="Logo">
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] leading-none" style="color: #9DC88D;">PT INDO MODA RAYA</p>
                            <p class="text-lg font-black mt-1 leading-none" style="font-family: 'Montserrat', sans-serif;">{{ config('app.name', 'Laporan Keuangan') }}</p>
                        </div>
                    </a>

                    <h1 class="mt-10 text-5xl font-black leading-tight tracking-tight" style="font-family: 'Montserrat', sans-serif;">
                        Kelola laporan keuangan perusahaan dari satu tempat.
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8" style="color: rgba(157,200,141,0.90);">
                        Login untuk mengakses dashboard, input transaksi operasional, pengeluaran, master data armada, dan rekap biaya lapangan.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-[1.75rem] border border-white/10 p-5 backdrop-blur-md" style="background: rgba(241,178,74,0.10);">
                            <p class="text-sm font-semibold text-white" style="font-family: 'Montserrat', sans-serif;">Pendapatan</p>
                            <p class="mt-2 text-sm" style="color: rgba(157,200,141,0.80);">Pantau arus masuk operasional per periode.</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/10 p-5 backdrop-blur-md" style="background: rgba(241,178,74,0.10);">
                            <p class="text-sm font-semibold text-white" style="font-family: 'Montserrat', sans-serif;">Biaya</p>
                            <p class="mt-2 text-sm" style="color: rgba(157,200,141,0.80);">Rekap biaya telly, paguyuban, dan pengeluaran lain.</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/10 p-5 backdrop-blur-md" style="background: rgba(241,178,74,0.10);">
                            <p class="text-sm font-semibold text-white" style="font-family: 'Montserrat', sans-serif;">Laporan</p>
                            <p class="mt-2 text-sm" style="color: rgba(157,200,141,0.80);">Ringkas, rapi, dan siap dipakai operasional harian.</p>
                        </div>
                    </div>
                </div>

                {{-- Right panel: Login Form --}}
                <div class="w-full bg-white p-8 sm:p-10 lg:border-l lg:border-stone-200">
                    <div class="mb-8 flex items-center gap-3 lg:hidden">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 shadow-sm overflow-hidden"
                              style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; background: #e8f5e0;">
                            <img src="{{ asset('logo.png') }}" class="h-8 w-8 object-contain" alt="Logo">
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em]" style="color: #4D774E;">PT INDO MODA RAYA</p>
                            <p class="text-base font-black mt-0.5 leading-none" style="font-family: 'Montserrat', sans-serif;">{{ config('app.name', 'Laporan Keuangan') }}</p>
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </div>
            </div>
        </div>
    </body>
</html>
