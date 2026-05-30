<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-label">Pengaturan Akun</p>
            <h2 class="page-title">Profil Admin</h2>
            <p class="page-sub">Kelola identitas admin, foto profil, dan keamanan sandi akun Anda.</p>
        </div>
    </x-slot>

    @php
        $customAvatarPath = public_path('avatar_' . $user->id . '.png');
        $customAvatarUrl = asset('avatar_' . $user->id . '.png');
        $defaultAvatarPath = public_path('avatar.png');
        $defaultAvatarUrl = asset('avatar.png');

        $avatarUrl = null;
        if (file_exists($customAvatarPath)) {
            $avatarUrl = $customAvatarUrl;
        } elseif (file_exists($defaultAvatarPath)) {
            $avatarUrl = $defaultAvatarUrl;
        }
    @endphp

    <div class="max-w-6xl space-y-6">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="relative p-6 sm:p-7" style="background: linear-gradient(135deg, #164A41 0%, #235f50 56%, #F1B24A 150%);">
                <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle at 18% 20%, #ffffff 0 1px, transparent 1px); background-size: 24px 24px;"></div>
                <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}?v={{ time() }}" class="h-20 w-20 rounded-2xl border-4 border-white/25 object-cover shadow-xl" style="width: 80px; height: 80px;" alt="Avatar Admin">
                        @else
                            <span class="flex h-20 w-20 items-center justify-center rounded-2xl border-4 border-white/25 bg-white/15 text-3xl font-black text-white shadow-xl" style="width: 80px; height: 80px;">
                                {{ strtoupper(str($user->name)->take(1)) }}
                            </span>
                        @endif
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.28em]" style="color: #F1B24A;">Akun Aktif</p>
                            <h3 class="mt-2 text-2xl font-black text-white sm:text-3xl" style="font-family:'Montserrat',sans-serif;">{{ $user->name }}</h3>
                            <p class="mt-1 text-sm font-medium text-white/75">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-white sm:min-w-72">
                        <div class="rounded-xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-white/60">Role</p>
                            <p class="mt-1 text-sm font-black">Admin</p>
                        </div>
                        <div class="rounded-xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-white/60">Status</p>
                            <p class="mt-1 text-sm font-black">Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1fr_0.82fr]">
            <section class="card p-0">
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl text-white" style="width: 44px; height: 44px; background: linear-gradient(135deg, #164A41, #4D774E);">
                            <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">Informasi Profil</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Ubah nama, email, dan foto profil admin.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            <div class="space-y-6">
                <section class="card p-0">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700" style="width: 44px; height: 44px;">
                                <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">Perbarui Kata Sandi</h3>
                                <p class="mt-0.5 text-xs text-slate-500">Gunakan sandi kuat agar akun tetap aman.</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </section>

                <section class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600" style="width: 44px; height: 44px;">
                            <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M5.07 19h13.86a2 2 0 001.76-2.95L13.76 4.1a2 2 0 00-3.52 0L3.31 16.05A2 2 0 005.07 19z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-wider text-rose-950">Zona Berbahaya</h3>
                            <p class="mt-0.5 text-xs text-rose-500">Hapus akun hanya jika benar-benar diperlukan.</p>
                        </div>
                    </div>
                    @include('profile.partials.delete-user-form')
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
