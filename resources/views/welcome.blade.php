<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laporan Keuangan') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-stone-950 font-sans text-stone-100">
        <div class="relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(251,191,36,0.18),_transparent_30%),radial-gradient(circle_at_right,_rgba(245,158,11,0.16),_transparent_25%),linear-gradient(180deg,_#1c1917_0%,_#0c0a09_100%)]">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,_transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,_transparent_1px)] bg-[size:64px_64px] opacity-40"></div>

            <header class="relative z-10 mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-400 text-sm font-black tracking-[0.35em] text-stone-950">
                        LK
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/80">Company Finance</p>
                        <p class="text-sm font-bold text-white">{{ config('app.name', 'Laporan Keuangan') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-stone-950/30">
                            Buka dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:border-white/30 hover:bg-white/5">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-full bg-amber-400 px-5 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-300">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            </header>

            <main class="relative z-10 mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-24">
                <section class="max-w-2xl">
                    <p class="inline-flex rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-amber-200">
                        Sistem laporan keuangan operasional
                    </p>
                    <h1 class="mt-8 text-5xl font-black leading-tight tracking-tight text-white sm:text-6xl">
                        Pantau transaksi, biaya lapangan, dan laba perusahaan dalam satu dashboard.
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-stone-300">
                        Aplikasi ini disiapkan untuk kebutuhan perusahaan angkutan dan operasional: master data pemilik, kendaraan, kapal, karyawan, transaksi operasional, pengeluaran, gaji telly, dan paguyuban.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        @auth
                            <a href="{{ route('transaksi-operasional.create') }}" class="rounded-full bg-amber-400 px-6 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-amber-500/20">
                                Input transaksi
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full bg-amber-400 px-6 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-amber-500/20">
                                Mulai dari login
                            </a>
                        @endauth
                        <a href="#fitur" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/30 hover:bg-white/5">
                            Lihat fitur
                        </a>
                    </div>

                    <div class="mt-12 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <p class="text-sm font-semibold text-amber-200">Operasional</p>
                            <p class="mt-2 text-sm leading-6 text-stone-300">Catat ritase, tonase, rute, telly, dan biaya lapangan.</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <p class="text-sm font-semibold text-amber-200">Laporan</p>
                            <p class="mt-2 text-sm leading-6 text-stone-300">Lihat ringkasan pendapatan, pengeluaran, dan laba bersih per bulan.</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <p class="text-sm font-semibold text-amber-200">Aman</p>
                            <p class="mt-2 text-sm leading-6 text-stone-300">Login bawaan Laravel Breeze untuk area admin dan pengelolaan akun.</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-white/10 bg-white/5 p-6 shadow-2xl shadow-black/30 backdrop-blur">
                    <div class="rounded-[1.5rem] bg-stone-900 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/80">Preview dashboard</p>
                                <h2 class="mt-2 text-2xl font-black text-white">Gambaran modul utama</h2>
                            </div>
                            <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">Siap dikembangkan</span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-[1.5rem] bg-white p-5 text-stone-950">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-500">Pendapatan</p>
                                <p class="mt-3 text-3xl font-black">Rp 128.500.000</p>
                                <p class="mt-2 text-sm text-stone-500">Contoh ringkasan per bulan</p>
                            </div>
                            <div class="rounded-[1.5rem] bg-amber-400 p-5 text-stone-950">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-700">Laba Bersih</p>
                                <p class="mt-3 text-3xl font-black">Rp 34.700.000</p>
                                <p class="mt-2 text-sm text-stone-700">Setelah biaya lapangan dan pengeluaran lain</p>
                            </div>
                        </div>

                        <div id="fitur" class="mt-6 space-y-3">
                            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 px-4 py-4">
                                <p class="font-semibold text-white">Master data lengkap</p>
                                <p class="mt-1 text-sm text-stone-300">Pemilik, kendaraan, kapal, dan karyawan bisa dikelola dari panel admin.</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 px-4 py-4">
                                <p class="font-semibold text-white">Transaksi operasional terhubung</p>
                                <p class="mt-1 text-sm text-stone-300">Input pendapatan sekaligus biaya telly dan paguyuban dalam satu form.</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 px-4 py-4">
                                <p class="font-semibold text-white">Siap deploy</p>
                                <p class="mt-1 text-sm text-stone-300">Struktur Laravel standar sehingga mudah dipindah ke hosting atau VPS.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
