@php
    // Automatically copy the generated avatar image to the public directory
    $avatarSrc = 'C:\\Users\\user\\.gemini\\antigravity\\brain\\86b2dd31-f82b-4e3e-ad79-43cb3323a077\\avatar_1779247668778.png';
    $avatarDest = public_path('avatar.png');
    if (file_exists($avatarSrc) && !file_exists($avatarDest)) {
        copy($avatarSrc, $avatarDest);
    }

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
        ['label' => 'Operasional','route' => 'operasional.index',   'active' => request()->routeIs('operasional.*')],
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
<div class="lg:flex lg:flex-shrink-0" style="width: 17rem;">
    <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>

        <div class="absolute inset-y-0 left-0 flex w-full max-w-xs">
            <aside
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-6 opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-6 opacity-0"
                class="flex w-full flex-col border-r border-white/10"
                style="background: linear-gradient(180deg, #164A41 0%, #0e3228 100%);"
            >
                {{-- macOS Window Controls (Visual details) --}}
                <div class="flex items-center gap-1.5 px-5 pt-4 pb-1">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f56]" style="width: 10px; height: 10px;"></span>
                    <span class="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]" style="width: 10px; height: 10px;"></span>
                    <span class="h-2.5 w-2.5 rounded-full bg-[#27c93f]" style="width: 10px; height: 10px;"></span>
                </div>

                {{-- Mobile: Brand --}}
                <div class="flex items-center justify-between gap-3 border-b border-white/10 px-5 py-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 overflow-hidden" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px; background: rgba(241,178,74,0.15);">
                            <img src="{{ asset('logo.png') }}" class="h-6 w-6 object-contain" alt="Logo">
                        </span>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-[0.25em] leading-none" style="color: #9DC88D;">Finance Ops</p>
                            <p class="text-xs font-black text-white mt-1 leading-none">Laporan Keuangan</p>
                        </div>
                    </a>
                    <button type="button" @click="sidebarOpen = false"
                        class="rounded-lg p-1.5 text-stone-300 hover:bg-white/10 hover:text-white transition-colors">
                        <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Mobile: Nav --}}
                <nav class="flex-1 overflow-y-auto px-3.5 py-4 space-y-4"
                    x-data="{ laporanOpen: {{ $laporanActive ? 'true' : 'false' }} }">

                    @foreach ($navGroups as $group)
                        @if ($group['label'])
                            <div class="px-2.5 pt-1">
                                <p class="text-[9px] font-bold uppercase tracking-[0.2em]" style="color: rgba(157,200,141,0.50);">{{ $group['label'] }}</p>
                            </div>
                        @endif

                        <div class="space-y-0.5">
                            @foreach ($group['items'] as $link)
                                <a href="{{ route($link['route']) }}"
                                   class="group flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs transition-all border hover:bg-white/8 hover:text-white"
                                   style="{{ $link['active']
                                       ? 'background: rgba(241,178,74,0.18); border-color: rgba(241,178,74,0.25); color: #fff; font-weight: 700;'
                                       : 'border-color: transparent; color: rgba(157,200,141,0.80);' }}">
                                    <svg class="h-4 w-4 flex-none transition-colors group-hover:text-white" style="width: 16px; height: 16px; {{ $link['active'] ? 'color: #F1B24A;' : 'color: rgba(157,200,141,0.60);' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons[$link['icon']] ?? '' !!}</svg>
                                    <span class="truncate">{{ $link['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Mobile: Laporan Group --}}
                    <div class="px-2.5 pt-1">
                        <p class="text-[9px] font-bold uppercase tracking-[0.2em]" style="color: rgba(157,200,141,0.50);">Laporan</p>
                    </div>
                    <div class="space-y-0.5">
                        <button type="button" @click="laporanOpen = !laporanOpen"
                            class="group flex w-full items-center justify-between gap-2.5 rounded-lg px-2.5 py-2 text-xs transition-all border hover:bg-white/8 hover:text-white"
                            style="{{ $laporanActive
                                ? 'background: rgba(241,178,74,0.18); border-color: rgba(241,178,74,0.25); color: #fff; font-weight: 700;'
                                : 'border-color: transparent; color: rgba(157,200,141,0.80);' }}">
                            <span class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 flex-none group-hover:text-white" style="width: 16px; height: 16px; {{ $laporanActive ? 'color: #F1B24A;' : 'color: rgba(157,200,141,0.60);' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons['laporan'] !!}</svg>
                                <span>Laporan</span>
                            </span>
                            <svg class="h-3 w-3 flex-none transition-transform duration-200 group-hover:text-white" style="width: 12px; height: 12px; {{ $laporanActive ? 'color: #F1B24A;' : 'color: rgba(157,200,141,0.60);' }}" :class="laporanOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="laporanOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="ml-3.5 mt-0.5 space-y-0.5 border-l border-white/10 pl-2.5">
                            @foreach ($laporanLinks as $item)
                                <a href="{{ route($item['route'], ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}"
                                   class="flex items-center rounded px-2 py-1.5 text-xs transition-all hover:text-white"
                                   style="{{ $item['active'] ? 'color: #F1B24A; font-weight: 700;' : 'color: rgba(157,200,141,0.70);' }}">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>

                {{-- Mobile: User info (Reference Card Style) --}}
                <div class="border-t border-white/10 px-4 py-4 bg-black/10">
                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-2.5 rounded-2xl border border-white/10 bg-white/5 p-3 transition-all hover:border-[#F1B24A]/40 hover:bg-white/10">
                        <div class="flex items-center gap-2.5 min-w-0">
                            @php
                                $customAvatarPath = public_path('avatar_' . Auth::id() . '.png');
                                $customAvatarUrl = asset('avatar_' . Auth::id() . '.png');
                                $defaultAvatarPath = public_path('avatar.png');
                                $defaultAvatarUrl = asset('avatar.png');
                                
                                $avatarUrl = null;
                                if (file_exists($customAvatarPath)) {
                                    $avatarUrl = $customAvatarUrl;
                                } elseif (file_exists($defaultAvatarPath)) {
                                    $avatarUrl = $defaultAvatarUrl;
                                }
                            @endphp
                            @if ($avatarUrl)
                                <img src="{{ $avatarUrl }}?v={{ time() }}" class="h-9 w-9 rounded-full object-cover border border-white/20" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px;" alt="Avatar">
                            @else
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-xs font-black text-white" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px;">
                                    {{ strtoupper(str(Auth::user()->name)->take(1)) }}
                                </span>
                            @endif
                            <div class="min-w-0">
                                <p class="truncate text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                                <p class="truncate text-[10px] mt-0.5" style="color: rgba(157,200,141,0.60);">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <span class="ml-auto flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#F1B24A] transition group-hover:bg-[#F1B24A] group-hover:text-stone-950">
                            <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </a>
                    <div class="mt-2.5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-white/10 border border-white/10 hover:bg-rose-600/20 hover:border-rose-600/30 py-2 text-[10px] font-bold text-white transition-all">Keluar</button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         DESKTOP SIDEBAR
         ═══════════════════════════════════════════════════════ --}}
    <aside class="sticky top-0 hidden h-screen w-68 flex-col border-r border-white/10 text-stone-100 lg:flex" style="background: linear-gradient(180deg, #164A41 0%, #0e3228 100%); width: 17rem; flex-shrink: 0;">
    {{-- macOS Window Controls (Visual details) --}}
    <div class="flex items-center gap-1.5 px-5 pt-4 pb-1">
        <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f56] transition hover:opacity-80" style="width: 10px; height: 10px;"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-[#ffbd2e] transition hover:opacity-80" style="width: 10px; height: 10px;"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-[#27c93f] transition hover:opacity-80" style="width: 10px; height: 10px;"></span>
    </div>

    {{-- Brand --}}
    <div class="flex items-center gap-2 px-5 py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 overflow-hidden" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px; background: rgba(241,178,74,0.15);">
                <img src="{{ asset('logo.png') }}" class="h-6 w-6 object-contain" alt="Logo">
            </span>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-[0.25em] leading-none" style="color: #9DC88D;">PT INDO MODA RAYA</p>
                <p class="text-xs font-black text-white mt-1.5 leading-none">Laporan Keuangan</p>
            </div>
        </a>
    </div>

    <div class="mx-4 border-t border-white/10"></div>

    {{-- Desktop: Nav --}}
    <nav class="flex-1 overflow-y-auto px-3.5 py-4 space-y-4"
        x-data="{ laporanOpen: {{ $laporanActive ? 'true' : 'false' }} }">

        @foreach ($navGroups as $group)
            @if ($group['label'])
                <div class="px-2.5 pt-1">
                    <p class="text-[9px] font-bold uppercase tracking-[0.25em]" style="color: rgba(157,200,141,0.50);">{{ $group['label'] }}</p>
                </div>
            @endif

            <div class="space-y-0.5">
                @foreach ($group['items'] as $link)
                    <a href="{{ route($link['route']) }}"
                       class="group flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs transition-all border hover:bg-white/8 hover:text-white"
                       style="{{ $link['active']
                           ? 'background: rgba(241,178,74,0.18); border-color: rgba(241,178,74,0.25); color: #fff; font-weight: 700;'
                           : 'border-color: transparent; color: rgba(157,200,141,0.80);' }}">
                        <svg class="h-5 w-5 flex-none transition-colors group-hover:text-white" style="width: 20px; height: 20px; {{ $link['active'] ? 'color: #F1B24A;' : 'color: rgba(157,200,141,0.60);' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons[$link['icon']] ?? '' !!}</svg>
                        <span class="truncate">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach

        {{-- Laporan Group --}}
        <div class="px-2.5 pt-1">
            <p class="text-[9px] font-bold uppercase tracking-[0.25em]" style="color: rgba(157,200,141,0.50);">Laporan</p>
        </div>
        <div class="space-y-0.5">
            <button type="button" @click="laporanOpen = !laporanOpen"
                class="group flex w-full items-center justify-between gap-2.5 rounded-lg px-2.5 py-2 text-xs transition-all border hover:bg-white/8 hover:text-white"
                style="{{ $laporanActive
                    ? 'background: rgba(241,178,74,0.18); border-color: rgba(241,178,74,0.25); color: #fff; font-weight: 700;'
                    : 'border-color: transparent; color: rgba(157,200,141,0.80);' }}">
                <span class="flex items-center gap-2.5">
                    <svg class="h-5 w-5 flex-none transition-colors group-hover:text-white" style="width: 20px; height: 20px; {{ $laporanActive ? 'color: #F1B24A;' : 'color: rgba(157,200,141,0.60);' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $svgIcons['laporan'] !!}</svg>
                    <span>Laporan</span>
                </span>
                <svg class="h-3.5 w-3.5 flex-none transition-transform duration-200 group-hover:text-white" style="width: 14px; height: 14px; {{ $laporanActive ? 'color: #F1B24A;' : 'color: rgba(157,200,141,0.60);' }}" :class="laporanOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                </svg>
            </button>

            <div x-show="laporanOpen" x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="ml-3.5 mt-0.5 space-y-0.5 border-l border-white/10 pl-2.5">
                @foreach ($laporanLinks as $item)
                    <a href="{{ route($item['route'], ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}"
                       class="flex items-center justify-between rounded px-2 py-1.5 text-[11px] transition-all hover:text-white"
                       style="{{ $item['active'] ? 'color: #F1B24A; font-weight: 700;' : 'color: rgba(157,200,141,0.70);' }}">
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

    </nav>

    {{-- ── Manajemen Pengguna ── --}}
    <div class="mx-3.5 mb-2">
        <a href="{{ route('admin.users.index') }}"
           class="group flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs transition-all border hover:bg-white/8 hover:text-white"
           style="{{ request()->routeIs('admin.users.*')
               ? 'background: rgba(241,178,74,0.18); border-color: rgba(241,178,74,0.25); color: #fff; font-weight: 700;'
               : 'border-color: transparent; color: rgba(157,200,141,0.80);' }}">
            <svg class="h-5 w-5 flex-none group-hover:text-white transition-colors" style="width:20px;height:20px; {{ request()->routeIs('admin.users.*') ? 'color: #F1B24A;' : 'color: rgba(157,200,141,0.60);' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>Manajemen Pengguna</span>
        </a>
    </div>

    <div class="mx-4 border-t border-white/10"></div>

    {{-- Desktop: User info (Reference Card Style) --}}
    <div class="px-4 py-4 bg-black/10">
        <a href="{{ route('profile.edit') }}" class="group flex items-center gap-2.5 rounded-2xl border border-white/10 bg-white/5 p-3 transition-all hover:border-[#F1B24A]/40 hover:bg-white/10">
            <div class="flex min-w-0 items-center gap-2.5">
                @php
                    $customAvatarPath = public_path('avatar_' . Auth::id() . '.png');
                    $customAvatarUrl = asset('avatar_' . Auth::id() . '.png');
                    $defaultAvatarPath = public_path('avatar.png');
                    $defaultAvatarUrl = asset('avatar.png');
                    
                    $avatarUrl = null;
                    if (file_exists($customAvatarPath)) {
                        $avatarUrl = $customAvatarUrl;
                    } elseif (file_exists($defaultAvatarPath)) {
                        $avatarUrl = $defaultAvatarUrl;
                    }
                @endphp
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}?v={{ time() }}" class="h-9 w-9 rounded-full object-cover border border-white/20" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px;" alt="Avatar">
                @else
                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-white/20 text-xs font-black text-white" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px;">
                        {{ strtoupper(str(Auth::user()->name)->take(1)) }}
                    </span>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                    <p class="truncate text-[10px] mt-0.5 leading-none" style="color: rgba(157,200,141,0.60);">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <span class="ml-auto flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#F1B24A] transition group-hover:bg-[#F1B24A] group-hover:text-stone-950">
                <svg class="h-3.5 w-3.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </a>
        <div class="mt-2.5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-white/10 border border-white/10 hover:bg-rose-600/20 hover:border-rose-600/30 py-2 text-[10px] font-bold text-white transition-all">Keluar</button>
            </form>
        </div>
    </div>
    </aside>
</div>
