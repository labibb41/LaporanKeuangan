<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laporan Keuangan') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-stone-900 antialiased">
        <div class="flex min-h-screen items-center justify-center bg-[radial-gradient(circle_at_top_left,_rgba(251,191,36,0.10),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(15,23,42,0.10),_transparent_35%),linear-gradient(180deg,_#f8fafc_0%,_#f1f5f9_100%)] px-4 py-10">
            <div class="w-full max-w-6xl overflow-hidden rounded-[2.75rem] border border-stone-200 bg-white shadow-2xl shadow-slate-200/60">
                <div class="grid lg:grid-cols-[0.95fr_0.75fr]">
                <div class="hidden p-10 text-white lg:block bg-stone-950">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <x-application-logo class="h-16 w-16" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/80">Finance Ops</p>
                            <p class="text-lg font-black">{{ config('app.name', 'Laporan Keuangan') }}</p>
                        </div>
                    </a>

                    <h1 class="mt-10 text-5xl font-black leading-tight tracking-tight">
                        Kelola laporan keuangan perusahaan dari satu tempat.
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-white/80">
                        Login untuk mengakses dashboard, input transaksi operasional, pengeluaran, master data armada, dan rekap biaya lapangan.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-[1.75rem] border border-white/15 bg-white/10 p-5">
                            <p class="text-sm font-semibold text-white">Pendapatan</p>
                            <p class="mt-2 text-sm text-white/75">Pantau arus masuk operasional per periode.</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/15 bg-white/10 p-5">
                            <p class="text-sm font-semibold text-white">Biaya</p>
                            <p class="mt-2 text-sm text-white/75">Rekap biaya telly, paguyuban, dan pengeluaran lain.</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/15 bg-white/10 p-5">
                            <p class="text-sm font-semibold text-white">Laporan</p>
                            <p class="mt-2 text-sm text-white/75">Ringkas, rapi, dan siap dipakai operasional harian.</p>
                        </div>
                    </div>
                </div>

                <div class="w-full bg-white p-8 sm:p-10 lg:border-l lg:border-stone-200">
                    <div class="mb-8 flex items-center gap-3 lg:hidden">
                        <x-application-logo class="h-14 w-14" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-700">Finance Ops</p>
                            <p class="text-base font-black text-stone-950">{{ config('app.name', 'Laporan Keuangan') }}</p>
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </div>
            </div>
        </div>
    </body>
</html>
