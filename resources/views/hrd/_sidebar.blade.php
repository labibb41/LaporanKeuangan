@php
$navItems = [
    ['label' => 'Dashboard',   'route' => 'hrd.dashboard',   'active' => request()->routeIs('hrd.dashboard'),   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
    ['label' => 'Rekap Gaji',  'route' => 'hrd.gaji',        'active' => request()->routeIs('hrd.gaji'),        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>'],
    ['label' => 'Operasional', 'route' => 'hrd.operasional',  'active' => request()->routeIs('hrd.operasional'),  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 18l4-2 5 2 5-2 4 2v-6l-4-2-5 2-5-2-4 2v6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 12V3m0 0l3 3m-3-3L9 6"/>'],
    ['label' => 'Laba Rugi',   'route' => 'hrd.keuangan',    'active' => request()->routeIs('hrd.keuangan'),    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
];
@endphp

<aside class="sticky top-0 h-screen flex flex-col text-white" style="background: linear-gradient(180deg, #164A41 0%, #0e3228 100%); width: 17rem;">
    {{-- macOS dots --}}
    <div class="flex items-center gap-1.5 px-5 pt-4 pb-1">
        <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f56]" style="width:10px;height:10px;"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]" style="width:10px;height:10px;"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-[#27c93f]" style="width:10px;height:10px;"></span>
    </div>

    {{-- Brand --}}
    <div class="flex items-center gap-2.5 border-b border-white/10 px-5 py-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10"
              style="width:36px;height:36px;min-width:36px;background:rgba(241,178,74,0.15);">
            <svg class="h-5 w-5 text-white" style="width:20px;height:20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L12 7.5l5.571 2.25m0 0L21.75 12l-4.179 2.25m0 0l-5.571-3-5.571 3M2.25 12l9.75 5.25 9.75-5.25"/>
            </svg>
        </span>
        <div>
            <p class="text-[9px] font-bold uppercase tracking-[0.25em] leading-none" style="color: #9DC88D;">Portal HRD</p>
            <p class="text-xs font-black mt-1 leading-none text-white" style="font-family: 'Montserrat', sans-serif;">{{ config('app.name') }}</p>
        </div>
    </div>

    {{-- Read-only badge --}}
    <div class="mx-4 mt-3 flex items-center gap-2 rounded-lg px-3 py-2 text-[10px] font-bold"
         style="background: rgba(241,178,74,0.15); color: #F1B24A; border: 1px solid rgba(241,178,74,0.25);">
        <svg class="h-3.5 w-3.5" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Mode Lihat Saja
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3.5 py-4 space-y-0.5">
        <p class="mb-2 px-2.5 text-[9px] font-bold uppercase tracking-[0.2em]" style="color: rgba(157,200,141,0.50);">Menu</p>
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs transition-all border"
               style="{{ $item['active']
                   ? 'background: rgba(241,178,74,0.18); border-color: rgba(241,178,74,0.25); color: #fff; font-weight: 700;'
                   : 'border-color: transparent; color: rgba(157,200,141,0.80);' }}">
                <svg class="h-4 w-4 flex-none" style="width:16px;height:16px;{{ $item['active'] ? 'color:#F1B24A;' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $item['icon'] !!}</svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- User Info --}}
    <div class="border-t border-white/10 px-4 py-4" style="background: rgba(0,0,0,0.10);">
        <div class="flex items-center gap-2.5 rounded-xl border border-white/10 p-3" style="background: rgba(255,255,255,0.05);">
            <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-black text-white flex-shrink-0"
                  style="width:32px;height:32px;min-width:32px;background:linear-gradient(135deg,#F1B24A,#d4962e);">
                {{ strtoupper(str(Auth::user()->name)->take(1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                <p class="truncate text-[10px] mt-0.5" style="color: rgba(157,200,141,0.60);">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2.5">
            @csrf
            <button type="submit" class="w-full rounded-xl border border-white/10 py-2 text-[10px] font-bold text-white transition-all hover:bg-rose-600/20 hover:border-rose-600/30"
                    style="background: rgba(255,255,255,0.08);">Keluar</button>
        </form>
    </div>
</aside>
