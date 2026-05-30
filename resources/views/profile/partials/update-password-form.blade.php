<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Password Saat Ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="field-white mt-1 block w-full py-3 text-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-1">
            <div>
                <x-input-label for="update_password_password" value="Password Baru" />
                <x-text-input id="update_password_password" name="password" type="password" class="field-white mt-1 block w-full py-3 text-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" value="Konfirmasi Password" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="field-white mt-1 block w-full py-3 text-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-3">
            <p class="text-xs leading-5 text-amber-800">Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol agar akun lebih aman.</p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="rounded-xl bg-[#164A41] px-5 py-3 hover:bg-[#0f3830] focus:ring-[#164A41]">
                Simpan Password
            </x-primary-button>

            @if (session('status') === 'password-updated')
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
