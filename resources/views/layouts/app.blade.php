<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laporan Keuangan') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <style>[x-cloak]{display:none !important}</style>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-50/80 font-sans text-stone-900 antialiased">
        <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen lg:flex">
            @include('layouts.navigation')

            <div class="flex min-w-0 flex-1 flex-col">
                {{-- ─── Top Header Bar ──────────────────────────────── --}}
                <header class="sticky top-0 z-30 border-b border-stone-200/60 bg-white/90 backdrop-blur-md">
                    <div class="flex items-center justify-between gap-4 px-5 py-3.5 sm:px-7">
                        {{-- Left: hamburger + breadcrumb --}}
                        <div class="flex min-w-0 items-center gap-3">
                            <button type="button" @click="sidebarOpen = true"
                                class="btn-icon border border-stone-200 bg-white text-stone-500 hover:bg-stone-50 hover:text-stone-800 lg:hidden">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-stone-900">{{ config('app.name', 'Laporan Keuangan') }}</p>
                                <p class="truncate text-xs text-stone-400 tracking-wider">Finance Operations Dashboard</p>
                            </div>
                        </div>

                        {{-- Right: user info + dropdown --}}
                        <div class="flex items-center gap-3">
                            {{-- Date chip --}}
                            <div class="hidden items-center gap-2 rounded-xl border border-stone-100 bg-stone-50 px-3 py-1.5 sm:flex">
                                <svg class="h-3.5 w-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs font-semibold text-stone-600">{{ now()->translatedFormat('j M Y') }}</span>
                            </div>

                            {{-- User badge --}}
                            <div class="hidden rounded-xl border border-stone-100 bg-stone-50 px-3 py-1.5 text-right sm:block">
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-stone-400">Akun Aktif</p>
                                <p class="text-xs font-bold text-stone-800">{{ Auth::user()->name }}</p>
                            </div>

                            {{-- Dropdown --}}
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-2.5 rounded-full border border-stone-200 bg-white py-1.5 pl-3 pr-1.5 text-sm font-semibold text-stone-700 shadow-sm transition hover:border-stone-300 hover:shadow-md active:scale-[0.98]">
                                        <span class="hidden sm:inline text-sm">Pengaturan</span>
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-amber-300 to-amber-500 text-xs font-black text-stone-900 shadow-sm">
                                            {{ strtoupper(str(Auth::user()->name)->take(1)) }}
                                        </span>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        <span class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            Profil
                                        </span>
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            <span class="flex items-center gap-2 text-rose-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                Keluar
                                            </span>
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                {{-- ─── Page Header (title/subtitle slot) ──────────── --}}
                @isset($header)
                    <div class="border-b border-stone-200/60 bg-white">
                        <div class="px-5 py-5 sm:px-7">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                {{-- ─── Main Content ────────────────────────────────── --}}
                <main class="flex-1 px-5 py-7 sm:px-7 animate-fadein">
                    {{-- Flash Messages --}}
                    @if (session('status') || session('success'))
                        <div class="mb-6 alert-success flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('status') ?? session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->has('delete'))
                        <div class="mb-6 alert-error flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $errors->first('delete') }}</span>
                        </div>
                    @endif

                    @if ($errors->any() && !$errors->has('delete'))
                        <div class="mb-6 alert-error flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-semibold">Terdapat kesalahan pada form:</p>
                                <ul class="mt-1 list-disc pl-4 text-xs">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="mx-auto w-full max-w-7xl">
                        {{ $slot }}
                    </div>
                </main>

                {{-- ─── Footer ──────────────────────────────────────── --}}
                <footer class="border-t border-stone-100 bg-white px-5 py-3 sm:px-7">
                    <p class="text-xs text-stone-400">© {{ date('Y') }} {{ config('app.name') }} · Finance Operations</p>
                </footer>
            </div>
        </div>
    </body>
</html>
