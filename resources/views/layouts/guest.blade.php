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
    <body class="min-h-screen bg-[#07120f] antialiased" style="font-family: 'Inter Tight', sans-serif; color: #1e293b;">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('auth-bg.svg') }}');"></div>
            <div class="absolute inset-0"
                 style="background: linear-gradient(90deg, rgba(5,13,11,0.84) 0%, rgba(8,28,24,0.66) 45%, rgba(5,13,11,0.38) 100%);"></div>
            <div class="absolute inset-x-0 top-0 h-40"
                 style="background: linear-gradient(180deg, rgba(2,8,7,0.78), transparent);"></div>

            <main class="relative z-10 min-h-screen px-5 py-6 sm:px-8 lg:px-14">
                <header class="mx-auto flex w-full max-w-7xl items-center justify-between bg-black/88 px-5 py-4 shadow-2xl shadow-black/20 backdrop-blur-sm sm:px-7">
                    <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-3">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-white/15 bg-white/10">
                            <img src="{{ asset('logo.png') }}" class="h-8 w-8 object-contain" alt="Logo">
                        </span>
                        <span class="min-w-0">
                            <span class="block text-[10px] font-semibold uppercase tracking-[0.28em] text-[#F1B24A]">PT INDO MODA RAYA</span>
                            <span class="block truncate text-base font-black leading-tight text-white" style="font-family: 'Montserrat', sans-serif;">{{ config('app.name', 'Laporan Keuangan') }}</span>
                        </span>
                    </a>
                </header>

                <div class="mx-auto grid min-h-[calc(100vh-6.5rem)] w-full max-w-7xl items-center gap-10 py-10 lg:grid-cols-[1fr_30rem] lg:gap-14">
                    <section class="max-w-4xl text-white">
                        <p class="text-sm font-bold uppercase tracking-[0.36em]" style="color: #F1B24A;">Ekspedisi Barang</p>
                        <h1 class="mt-6 max-w-4xl text-5xl font-black leading-[1.02] tracking-tight sm:text-6xl lg:text-7xl" style="font-family: 'Montserrat', sans-serif;">
                            Kelola laporan operasional perusahaan dari satu tempat.
                        </h1>
                        <p class="mt-6 max-w-3xl text-xl font-semibold leading-9 text-white/90 sm:text-2xl sm:leading-10">
                            Pantau transaksi, pengeluaran, armada, dan rekap biaya lapangan dengan tampilan yang siap dipakai setiap hari.
                        </p>

                        <div class="mt-9 flex flex-wrap gap-3">
                            <span class="border-l-4 border-[#F1B24A] bg-black/35 px-5 py-3 text-sm font-black text-white backdrop-blur-sm">Transaksi cepat</span>
                            <span class="border-l-4 border-[#F1B24A] bg-black/35 px-5 py-3 text-sm font-black text-white backdrop-blur-sm">Data armada rapi</span>
                            <span class="border-l-4 border-[#F1B24A] bg-black/35 px-5 py-3 text-sm font-black text-white backdrop-blur-sm">Laporan siap baca</span>
                        </div>
                    </section>

                    <section class="flex justify-center lg:justify-end">
                        <div class="w-full rounded-[1.75rem] bg-white px-6 py-8 shadow-2xl shadow-black/30 sm:px-8">
                            {{ $slot }}
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </body>
</html>
