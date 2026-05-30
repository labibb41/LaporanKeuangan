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

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=1">

        <style>[x-cloak]{display:none !important}</style>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="min-h-screen bg-slate-100 antialiased" style="font-family: 'Inter Tight', 'Montserrat', sans-serif; color: #1e293b;">
        <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen lg:flex">
            @include('layouts.navigation')

            <div class="flex min-w-0 flex-1 flex-col">
                {{-- ─── Top Header Bar ──────────────────────────────── --}}
                <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur-md">
                    <div class="flex items-center justify-between gap-4 px-5 py-3 sm:px-7">
                        {{-- Left: hamburger + breadcrumb --}}
                        <div class="flex min-w-0 items-center gap-3">
                            <button type="button" @click="sidebarOpen = true"
                                class="btn-icon border border-stone-200 bg-white text-stone-500 hover:bg-stone-50 hover:text-stone-800 lg:hidden">
                                <svg class="h-5 w-5" style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-stone-950">{{ config('app.name', 'Laporan Keuangan') }}</p>
                                <p class="truncate text-[10px] font-semibold text-stone-400 uppercase tracking-wider">Finance Operations Dashboard</p>
                            </div>
                        </div>

                        {{-- Middle: Search Bar with shortcut ⌘K --}}
                        <div class="hidden max-w-xs flex-1 md:block">
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-stone-400" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" placeholder="Search..." class="w-full rounded-xl border border-stone-200 bg-stone-50/70 pl-9 pr-12 py-1.5 text-xs text-stone-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/10" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <kbd class="inline-flex items-center rounded border border-stone-200 bg-white px-1.5 font-sans text-[10px] font-medium text-stone-400 shadow-sm">⌘K</kbd>
                                </div>
                            </div>
                        </div>

                        {{-- Right: user info + dropdown --}}
                        <div class="flex items-center gap-3">
                            {{-- Date chip --}}
                            <div class="hidden items-center gap-2 rounded-xl border border-stone-100 bg-stone-50/60 px-3 py-1.5 sm:flex">
                                <svg class="h-3.5 w-3.5 text-stone-400" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs font-semibold text-stone-600">{{ now()->translatedFormat('j M Y') }}</span>
                            </div>

                            {{-- Notification Bell --}}
                            <div class="relative">
                                <button id="activityBell" type="button" class="relative rounded-xl border border-stone-200 bg-white p-2 text-stone-500 transition hover:bg-stone-50 hover:text-stone-800">
                                    <svg class="h-4.5 w-4.5" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span id="activityPulse" class="absolute right-1.5 top-1.5 hidden h-2 w-2">
                                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex h-2 w-2 rounded-full bg-rose-500"></span>
                                    </span>
                                    <span id="activityCount" class="absolute -right-1.5 -top-1.5 hidden min-w-5 rounded-full bg-rose-600 px-1.5 py-0.5 text-center text-[10px] font-black leading-none text-white">0</span>
                                </button>

                                <div id="activityDropdown" class="absolute right-0 z-50 mt-2 hidden w-80 overflow-hidden rounded-2xl border border-stone-100 bg-white shadow-xl ring-1 ring-black/5">
                                    <div class="flex items-center justify-between border-b border-stone-100 px-4 py-3">
                                        <div>
                                            <p class="text-sm font-black text-stone-950">Notifikasi</p>
                                            <p class="text-[11px] text-stone-500">Perubahan terbaru dari admin lain</p>
                                        </div>
                                        <span id="activityStatus" class="rounded-full bg-stone-100 px-2 py-1 text-[10px] font-bold text-stone-500">0 baru</span>
                                    </div>
                                    <div id="activityList" class="max-h-80 overflow-y-auto p-2">
                                        <div id="activityEmpty" class="px-4 py-8 text-center">
                                            <p class="text-sm font-semibold text-stone-700">Belum ada notifikasi baru</p>
                                            <p class="mt-1 text-xs text-stone-400">Perubahan admin lain akan muncul di sini.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Dropdown Profile (Alpine.js) --}}
                            <div x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false" class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white py-1 pl-3 pr-1 text-sm font-semibold text-stone-700 shadow-sm transition hover:border-stone-300 hover:shadow-md active:scale-[0.98]">
                                    <span class="hidden sm:inline text-xs font-bold text-stone-700">{{ Auth::user()->name }}</span>
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
                                        <img src="{{ $avatarUrl }}?v={{ time() }}" class="h-7 w-7 rounded-lg object-cover border border-stone-100" style="width: 28px; height: 28px; min-width: 28px; min-height: 28px;" alt="Avatar">
                                    @else
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-black text-white shadow-sm" style="width: 28px; height: 28px; min-width: 28px; min-height: 28px; background: linear-gradient(135deg, #164A41, #4D774E);">
                                            {{ strtoupper(str(Auth::user()->name)->take(1)) }}
                                        </span>
                                    @endif
                                </button>
                                
                                <div x-show="dropdownOpen" x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl border border-stone-100 bg-white p-1.5 shadow-lg ring-1 ring-black/5 z-50">
                                    
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs text-stone-700 hover:bg-stone-50 transition">
                                        <svg class="h-4 w-4 text-stone-400" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profil
                                    </a>
                                    
                                    <div class="my-1 border-t border-stone-100"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 transition">
                                            <svg class="h-4 w-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- ─── Page Header (title/subtitle slot) ──────────── --}}
                @isset($header)
                    <div class="border-b border-slate-200/80" style="background: linear-gradient(90deg, #fff 0%, #f0faf4 100%)">
                        <div class="px-5 py-3.5 sm:px-7">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                {{-- ─── Main Content ────────────────────────────────── --}}
                <main class="flex-1 px-5 py-5 sm:px-7 animate-fadein">
                    {{-- Flash Messages --}}

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

        @if (session('status') || session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: "{{ session('status') ?? session('success') }}"
                });
            });
        </script>
        @endif
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let lastChecked = "{{ now()->toIso8601String() }}";
                let unreadCount = 0;
                let activityItems = [];
                const activityBell = document.getElementById('activityBell');
                const activityDropdown = document.getElementById('activityDropdown');
                const activityPulse = document.getElementById('activityPulse');
                const activityCount = document.getElementById('activityCount');
                const activityStatus = document.getElementById('activityStatus');
                const activityList = document.getElementById('activityList');
                const activityEmpty = document.getElementById('activityEmpty');

                const ActivityToast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                function actionTitle(action) {
                    if (action === 'created') return 'Data Baru';
                    if (action === 'updated') return 'Data Diperbarui';
                    if (action === 'deleted') return 'Data Dihapus';
                    return 'Aktivitas Baru';
                }

                function actionBadgeClass(action) {
                    if (action === 'created') return 'bg-emerald-50 text-emerald-700';
                    if (action === 'updated') return 'bg-amber-50 text-amber-700';
                    if (action === 'deleted') return 'bg-rose-50 text-rose-700';
                    return 'bg-slate-100 text-slate-700';
                }

                function escapeHtml(value) {
                    return String(value ?? '').replace(/[&<>"']/g, function(char) {
                        return {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        }[char];
                    });
                }

                function updateBadge() {
                    if (!activityPulse || !activityCount || !activityStatus) return;

                    const hasUnread = unreadCount > 0;
                    activityPulse.classList.toggle('hidden', !hasUnread);
                    activityCount.classList.toggle('hidden', !hasUnread);
                    activityCount.textContent = unreadCount > 9 ? '9+' : unreadCount;
                    activityStatus.textContent = `${unreadCount} baru`;
                    activityStatus.className = hasUnread
                        ? 'rounded-full bg-rose-50 px-2 py-1 text-[10px] font-bold text-rose-700'
                        : 'rounded-full bg-stone-100 px-2 py-1 text-[10px] font-bold text-stone-500';
                }

                function renderActivityList() {
                    if (!activityList || !activityEmpty) return;

                    activityList.querySelectorAll('[data-activity-item]').forEach(item => item.remove());
                    activityEmpty.classList.toggle('hidden', activityItems.length > 0);

                    activityItems.slice(0, 8).forEach(log => {
                        const link = document.createElement('a');
                        link.href = log.url || '#';
                        link.dataset.activityItem = 'true';
                        link.className = 'block rounded-xl px-3 py-3 transition hover:bg-stone-50';
                        link.innerHTML = `
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-black ${actionBadgeClass(log.action)}">${escapeHtml(log.model_label || 'Data')}</span>
                                        <span class="text-[10px] font-semibold text-stone-400">${escapeHtml(log.created_at_human || '')}</span>
                                    </div>
                                    <p class="mt-2 text-xs font-black text-stone-900">${escapeHtml(actionTitle(log.action))}</p>
                                    <p class="mt-1 text-xs leading-5 text-stone-500">${escapeHtml(log.description)}</p>
                                </div>
                            </div>
                        `;
                        activityList.appendChild(link);
                    });
                }

                function addActivities(logs) {
                    logs.forEach(log => {
                        if (activityItems.some(item => item.id === log.id)) return;

                        activityItems.unshift(log);
                        unreadCount += 1;

                        let icon = 'info';
                        if (log.action === 'created') icon = 'success';
                        if (log.action === 'deleted') icon = 'warning';

                        ActivityToast.fire({
                            icon: icon,
                            title: actionTitle(log.action),
                            text: log.description
                        });
                    });

                    activityItems = activityItems.slice(0, 12);
                    updateBadge();
                    renderActivityList();
                }

                activityBell?.addEventListener('click', function(event) {
                    event.stopPropagation();
                    activityDropdown?.classList.toggle('hidden');
                    unreadCount = 0;
                    updateBadge();
                });

                document.addEventListener('click', function(event) {
                    if (!activityDropdown || !activityBell) return;
                    if (activityDropdown.contains(event.target) || activityBell.contains(event.target)) return;
                    activityDropdown.classList.add('hidden');
                });

                function checkActivity() {
                    fetch(`/api/activity-logs?since=${encodeURIComponent(lastChecked)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.logs && data.logs.length > 0) {
                            let shouldReload = false;
                            addActivities(data.logs);
                            shouldReload = true;

                            if (shouldReload) {
                                const isTransaksiPage = window.location.pathname.includes('/transaksi-operasional');
                                const isOperasionalPage = window.location.pathname === '/operasional' || window.location.pathname.endsWith('/operasional');
                                const modalEl = document.querySelector('[x-show="showFormModal"]');
                                const isEditing = modalEl && modalEl.style.display !== 'none';
                                
                                const previewEl = document.querySelector('[x-show="showPreviewModal"]');
                                const isPreviewing = previewEl && previewEl.style.display !== 'none';

                                if ((isTransaksiPage && !isEditing) || isOperasionalPage) {
                                    if (isTransaksiPage && isPreviewing) {
                                        const alpineEl = document.querySelector('[x-data]');
                                        if (alpineEl && alpineEl.__x && alpineEl.__x.$data) {
                                            alpineEl.__x.$data.needsReload = true;
                                        }
                                    } else {
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1500);
                                    }
                                }
                            }
                        }
                        if (data.timestamp) {
                            lastChecked = data.timestamp;
                        }
                    })
                    .catch(err => console.debug('Polling activity logs error:', err));
                }

                updateBadge();
                renderActivityList();

                // Poll every 5 seconds
                setInterval(checkActivity, 5000);
            });
        </script>
    </body>
</html>
