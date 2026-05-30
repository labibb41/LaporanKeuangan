<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
            <label for="avatar" class="block text-xs font-black uppercase tracking-wider text-slate-700">Foto Profil</label>
            <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-center">
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

                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}?v={{ time() }}" class="h-20 w-20 rounded-2xl border-4 border-white object-cover shadow-sm" style="width: 80px; height: 80px;" alt="Avatar Current">
                @else
                    <span class="flex h-20 w-20 items-center justify-center rounded-2xl border-4 border-white bg-emerald-50 text-2xl font-black text-[#164A41] shadow-sm" style="width: 80px; height: 80px;">
                        {{ strtoupper(str($user->name)->take(1)) }}
                    </span>
                @endif

                <div class="min-w-0 flex-1">
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full cursor-pointer rounded-xl border border-slate-200 bg-white text-xs text-slate-500 shadow-sm file:mr-4 file:border-0 file:bg-[#164A41] file:px-4 file:py-3 file:text-xs file:font-black file:text-white hover:file:bg-[#0f3830]">
                    <p class="mt-2 text-[11px] text-slate-500">Format JPEG, PNG, atau JPG. Ukuran maksimal 2MB.</p>
                </div>
            </div>
            @error('avatar')
                <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="name" value="Nama Admin" />
                <x-text-input id="name" name="name" type="text" class="field-white mt-1 block w-full py-3 text-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="field-white mt-1 block w-full py-3 text-sm" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                <p class="text-xs font-medium text-amber-800">
                    Email Anda belum diverifikasi.
                    <button form="send-verification" class="font-bold underline hover:text-amber-950">
                        Kirim ulang email verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-semibold text-green-700">
                        Link verifikasi baru sudah dikirim ke email Anda.
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button class="rounded-xl bg-[#164A41] px-5 py-3 hover:bg-[#0f3830] focus:ring-[#164A41]">
                Simpan Profil
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-emerald-700"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
