<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-label">Pengaturan Akun</p>
            <h2 class="page-title">Profil Admin</h2>
            <p class="page-sub">Kelola informasi profil, foto profil, dan keamanan sandi akun Anda.</p>
        </div>
    </x-slot>

    <div class="max-w-4xl space-y-6">
        {{-- Profile Information Card --}}
        <section class="card">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600" style="width: 36px; height: 36px;">
                    <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider">Informasi Profil</h3>
                    <p class="text-[10px] text-stone-400">Ubah nama, email, dan unggah foto profil Anda</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        {{-- Update Password Card --}}
        <section class="card">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600" style="width: 36px; height: 36px;">
                    <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider">Perbarui Kata Sandi</h3>
                    <p class="text-[10px] text-stone-400">Pastikan akun Anda menggunakan kata sandi yang kuat dan aman</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        {{-- Delete Account Card --}}
        <section class="card border-rose-100 bg-rose-50/10">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600" style="width: 36px; height: 36px;">
                    <svg class="h-5 w-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-black text-rose-900 uppercase tracking-wider">Hapus Akun</h3>
                    <p class="text-[10px] text-rose-500">Tindakan ini permanen dan tidak dapat dibatalkan</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </div>
</x-app-layout>
