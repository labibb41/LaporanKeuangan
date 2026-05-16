@php
    $navGroups = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Dashboard',        'route' => 'dashboard',                    'active' => request()->routeIs('dashboard'),                  'icon' => 'dashboard'],
                ['label' => 'Database General', 'route' => 'transaksi-operasional.index',  'active' => request()->routeIs('transaksi-operasional.*'),    'icon' => 'database'],
            ],
        ],
        [
            'label' => 'Master Data',
            'items' => [
                ['label' => 'Pemilik',   'route' => 'pemilik.index',   'active' => request()->routeIs('pemilik.*'),   'icon' => 'pemilik'],
                ['label' => 'Kendaraan', 'route' => 'kendaraan.index', 'active' => request()->routeIs('kendaraan.*'), 'icon' => 'kendaraan'],
                ['label' => 'Kapal',     'route' => 'kapal.index',     'active' => request()->routeIs('kapal.*'),     'icon' => 'kapal'],
                ['label' => 'Karyawan',  'route' => 'karyawan.index',  'active' => request()->routeIs('karyawan.*'),  'icon' => 'karyawan'],
            ],
        ],
    ];

    $laporanLinks = [
        ['label' => 'Rekapan',    'route' => 'laporan.index',       'active' => request()->routeIs('laporan.index')],
        ['label' => 'Operasional','route' => 'laporan.operasional', 'active' => request()->routeIs('laporan.operasional') || request()->routeIs('operasional.*')],
        ['label' => 'Partner',    'route' => 'laporan.partner',     'active' => request()->routeIs('laporan.partner')],
        ['label' => 'Gaji Telly', 'route' => 'laporan.telly',       'active' => request()->routeIs('laporan.telly')],
        ['label' => 'Paguyuban',  'route' => 'laporan.paguyuban',   'active' => request()->routeIs('laporan.paguyuban')],
        ['label' => 'Pengeluaran','route' => 'laporan.pengeluaran', 'active' => request()->routeIs('laporan.pengeluaran')],
        ['label' => 'Keuangan',   'route' => 'laporan.keuangan',    'active' => request()->routeIs('laporan.keuangan')],
    ];

    $laporanActive = request()->routeIs('laporan.*') || request()->routeIs('operasional.*');

    $svgIcons = [
        'dashboard'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
        'database'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 7a8 3 0 0116 0M4 7v10a8 3 0 0016 0V7M4 12a8 3 0 0016 0"/>',
        'pemilik'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'kendaraan'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17a2 2 0 104 0M5 17a2 2 0 104 0m-4 0h14a2 2 0 002-2v-3a2 2 0 00-2-2h-1l-2-5H6L4 10H3a2 2 0 00-2 2v3a2 2 0 002 2h2"/>',
        'kapal'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 12V3m0 0l3 3m-3-3L9 6"/>',
        'karyawan'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>',
        'laporan'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v8m16 0H3"/>',
    ];
@endphp

{{-- ═══════════════════════════════════════════════════════
     MOBILE SIDEBAR OVERLAY
═══════════════════════════════════════════════════════ --}}
<div class="lg:flex lg:flex-shrink-0">
    <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-stone-950/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>

        <div class="absolute inset-y-0 left-0 flex w-full max-w-xs">
            <aside
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-6 opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-6 opacity-0"
                class="flex w-full flex-col bg-white shadow-2xl"
            >
                {{-- Mobile: Brand --}}
                <div class="flex items-center justify-between gap-3 border-b border-stone-100 px-4 py-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-stone-950 text-sm font-black text-amber-300 shadow-sm">
                            LK
                        </span>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Finance Ops</p>
                            <p class="text-sm font-black text-stone-900">{{ config('app.name', 'Laporan Keuangan') }}</p>
                        </div>
                    </a>
                    <button type="button" @click="sidebarOpen = false"
                        class="rounded-xl p-2 text-stone-400 hover:bg-stone-100 hover:text-stone-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Mobile: Nav --}}
                <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5"
                    x-data="{ laporanOpen: {{ $laporanActive ? 'true' : 'false' }} }">

                    @foreach ($navGroups as $group)
                        @if ($group['label'])
                            <div class="px-3 pt-1">
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400">{{ $group['label'] }}</p>
                            </div>
                        @endif

                        <div class="space-y-0.5">
                            @foreach ($group['items'] as $link)
                                <a href="{{ route($link['route']) }}"
                                   class="{{ $link['active'] ? 'bg-sky-50 text-sky-700' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all">
                                    <svg class="h-4.5 w-4.5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons[$link['icon']] ?? '' !!}</svg>
                                    <span class="truncate">{{ $link['label'] }}</span>
                                    @if ($link['active'])
                                        <span class="ml-auto h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Laporan Group --}}
                    <div class="px-3 pt-1">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400">Laporan</p>
                    </div>
                    <div class="space-y-0.5">
                        <button type="button" @click="laporanOpen = !laporanOpen"
                            class="{{ $laporanActive ? 'bg-sky-50 text-sky-700' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }} flex w-full items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all">
                            <span class="flex items-center gap-3">
                                <svg class="h-4.5 w-4.5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons['laporan'] !!}</svg>
                                <span>Laporan</span>
                            </span>
                            <svg class="h-3.5 w-3.5 flex-none transition-transform duration-200" :class="laporanOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="laporanOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="ml-4 mt-1 space-y-0.5 border-l-2 border-stone-100 pl-3">
                            @foreach ($laporanLinks as $item)
                                <a href="{{ route($item['route'], ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}"
                                   class="{{ $item['active'] ? 'text-sky-700 font-bold' : 'text-stone-500 hover:text-stone-900 hover:bg-stone-50' }} flex items-center rounded-lg px-2.5 py-2 text-sm font-medium transition-all">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>

                {{-- Mobile: User info --}}
                <div class="border-t border-stone-100 px-3 py-4">
                    <div class="mb-3 flex items-center gap-3 rounded-xl bg-stone-50 px-3 py-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-400 text-xs font-black text-stone-900">
                            {{ strtoupper(str(Auth::user()->name)->take(1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-stone-900">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-stone-400">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('profile.edit') }}" class="rounded-xl bg-stone-100 px-3 py-2 text-center text-xs font-semibold text-stone-700 hover:bg-stone-200">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">Keluar</button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         DESKTOP SIDEBAR
    ═══════════════════════════════════════════════════════ --}}
    <aside class="sticky top-0 hidden h-screen w-68 flex-col bg-stone-950 text-stone-100 lg:flex" style="width: 17rem;">
        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-300 to-amber-500 text-sm font-black text-stone-950 shadow-md">
                    LK
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-amber-300/70">Finance Ops</p>
                    <p class="text-sm font-black text-white leading-tight">{{ config('app.name', 'Laporan Keuangan') }}</p>
                </div>
            </a>
        </div>

        <div class="mx-4 border-t border-stone-800/60"></div>

        {{-- Desktop: Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5"
            x-data="{ laporanOpen: {{ $laporanActive ? 'true' : 'false' }} }">

            @foreach ($navGroups as $group)
                @if ($group['label'])
                    <div class="px-3 pt-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-stone-500">{{ $group['label'] }}</p>
                    </div>
                @endif

                <div class="space-y-0.5">
                    @foreach ($group['items'] as $link)
                        <a href="{{ route($link['route']) }}"
                           class="{{ $link['active'] ? 'bg-stone-800 text-amber-300' : 'text-stone-400 hover:bg-stone-900/70 hover:text-white' }} group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all">
                            <svg class="h-[18px] w-[18px] flex-none {{ $link['active'] ? 'text-amber-300' : 'text-stone-500 group-hover:text-stone-300' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons[$link['icon']] ?? '' !!}</svg>
                            <span class="truncate">{{ $link['label'] }}</span>
                            @if ($link['active'])
                                <span class="ml-auto flex h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach

            {{-- Laporan Group --}}
            <div class="px-3 pt-2">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-stone-500">Laporan</p>
            </div>
            <div class="space-y-0.5">
                <button type="button" @click="laporanOpen = !laporanOpen"
                    class="{{ $laporanActive ? 'bg-stone-800 text-amber-300' : 'text-stone-400 hover:bg-stone-900/70 hover:text-white' }} group flex w-full items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all">
                    <span class="flex items-center gap-3">
                        <svg class="h-[18px] w-[18px] flex-none {{ $laporanActive ? 'text-amber-300' : 'text-stone-500 group-hover:text-stone-300' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons['laporan'] !!}</svg>
                        <span>Laporan</span>
                    </span>
                    <svg class="h-3.5 w-3.5 flex-none transition-transform duration-200" :class="laporanOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <div x-show="laporanOpen" x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="ml-4 mt-1 space-y-0.5 border-l-2 border-stone-800 pl-3">
                    @foreach ($laporanLinks as $item)
                        <a href="{{ route($item['route'], ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}"
                           class="{{ $item['active'] ? 'text-amber-300 font-semibold' : 'text-stone-500 hover:text-stone-200 hover:bg-stone-900/50' }} flex items-center justify-between rounded-lg px-2.5 py-2 text-sm transition-all">
                            <span>{{ $item['label'] }}</span>
                            @if ($item['active'])
                                <span class="h-1 w-1 rounded-full bg-amber-400"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>

        <div class="mx-4 border-t border-stone-800/60"></div>

        {{-- Desktop: User info --}}
        <div class="px-3 py-4">
            <div class="mb-2.5 flex items-center gap-3 rounded-xl bg-stone-900/60 px-3 py-3">
                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-amber-300 to-amber-500 text-xs font-black text-stone-900">
                    {{ strtoupper(str(Auth::user()->name)->take(1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-stone-400">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('profile.edit') }}" class="rounded-xl bg-stone-900/60 px-3 py-2 text-center text-xs font-semibold text-stone-300 transition hover:bg-stone-900 hover:text-white">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-rose-600/15 px-3 py-2 text-xs font-semibold text-rose-300 transition hover:bg-rose-600/25 hover:text-rose-200">Keluar</button>
                </form>
            </div>
        </div>
    </aside>
</div>
