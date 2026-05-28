<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal HRD — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=1">

    <style>[x-cloak]{display:none !important}</style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen antialiased" style="font-family: 'Inter Tight', 'Montserrat', sans-serif; background: #f6faf7; color: #1e293b;">
<div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen lg:flex">

    {{-- ══════════ MOBILE OVERLAY ══════════ --}}
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden">
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>
        <div class="absolute inset-y-0 left-0 w-72">
            @include('hrd._sidebar')
        </div>
    </div>

    {{-- ══════════ DESKTOP SIDEBAR ══════════ --}}
    <div class="hidden lg:block" style="width: 17rem; flex-shrink: 0;">
        @include('hrd._sidebar')
    </div>

    {{-- ══════════ MAIN AREA ══════════ --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Top Header --}}
        <header class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/95 backdrop-blur-md">
            <div class="flex items-center justify-between gap-4 px-5 py-3 sm:px-7">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = true"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-stone-200 bg-white text-stone-500 hover:bg-stone-50 lg:hidden">
                        <svg class="h-5 w-5" style="width:20px;height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <p class="text-sm font-bold text-stone-950">{{ config('app.name') }}</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color: #4D774E;">Portal HRD — Mode Lihat Saja</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Badge Mode --}}
                    <span class="hidden items-center gap-1.5 rounded-full border px-3 py-1 text-[10px] font-bold sm:inline-flex"
                          style="background: #f0faf4; border-color: #9DC88D; color: #164A41;">
                        <span class="h-1.5 w-1.5 rounded-full" style="background: #4D774E;"></span>
                        READ ONLY
                    </span>

                    {{-- Date --}}
                    <div class="hidden items-center gap-1.5 rounded-xl border border-stone-100 bg-stone-50/60 px-3 py-1.5 sm:flex">
                        <svg class="h-3.5 w-3.5 text-stone-400" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs font-semibold text-stone-600">{{ now()->translatedFormat('j M Y') }}</span>
                    </div>

                    {{-- User Dropdown --}}
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button @click="open = !open"
                            class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white py-1 pl-3 pr-1 text-xs font-bold text-stone-700 shadow-sm hover:border-stone-300 hover:shadow-md transition">
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-black text-white"
                                  style="width:28px;height:28px;min-width:28px;background:linear-gradient(135deg,#164A41,#4D774E);">
                                {{ strtoupper(str(Auth::user()->name)->take(1)) }}
                            </span>
                        </button>
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl border border-stone-100 bg-white p-1.5 shadow-lg z-50">
                            <div class="my-1 border-t border-stone-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Header --}}
        @isset($header)
            <div class="border-b border-slate-200/70 px-5 py-3.5 sm:px-7" style="background: linear-gradient(90deg, #fff 0%, #f0faf4 100%);">
                {{ $header }}
            </div>
        @endisset

        {{-- Main Content --}}
        <main class="flex-1 px-5 py-5 sm:px-7 animate-fadein">
            @if (session('status') || session('success'))
                <div class="mb-4 alert-success flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('status') ?? session('success') }}
                </div>
            @endif
            <div class="mx-auto w-full max-w-7xl">
                {{ $slot }}
            </div>
        </main>

        <footer class="border-t border-stone-100 bg-white px-5 py-3 sm:px-7">
            <p class="text-xs text-stone-400">© {{ date('Y') }} {{ config('app.name') }} · Portal HRD — Hanya Lihat</p>
        </footer>
    </div>
</div>

@if (session('status') || session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
        Toast.fire({ icon: 'success', title: "{{ session('status') ?? session('success') }}" });
    });
</script>
@endif
</body>
</html>
