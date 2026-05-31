<x-guest-layout>
    <div class="mx-auto w-full max-w-[27rem]">
        <div class="mb-6 text-center">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.28em]" style="color: #4D774E;">Portal Masuk</p>
            <h1 class="text-3xl font-black tracking-tight text-stone-950" style="font-family: 'Montserrat', sans-serif;">Form Registrasi</h1>
            <p class="mt-2 text-sm leading-6 text-stone-600">Buat akun baru untuk mengakses sistem.</p>
        </div>

        <div class="mx-auto mb-7 flex max-w-sm rounded-full bg-stone-100 p-1">
            <a href="{{ route('login') }}" class="flex-1 rounded-full px-4 py-2.5 text-center text-sm font-black text-stone-700 transition hover:text-stone-950">Login</a>
            <a href="{{ route('register') }}" class="flex-1 rounded-full px-4 py-2.5 text-center text-sm font-black text-white shadow-sm transition" style="background-color: #164A41;">Daftar</a>
        </div>

        <div x-data="{ loading: false }" class="relative">
            <div x-show="loading" x-cloak class="absolute inset-0 z-20 rounded-[2rem] bg-white/70 backdrop-blur flex items-center justify-center">
                <div class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-white px-5 py-4 shadow-sm">
                    <svg class="h-5 w-5 animate-spin text-[#164A41]" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <p class="text-sm font-semibold text-stone-700">Membuat akun...</p>
                </div>
            </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4" x-on:submit="loading = true">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 flex items-center text-stone-400" style="left: 1rem;">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20a8 8 0 0116 0" />
                        </svg>
                    </span>
                    <x-text-input id="name" class="block w-full rounded-xl border-0 bg-stone-100/70 !pl-14 pr-4 py-3 text-sm shadow-none placeholder:text-stone-400 transition focus:bg-white focus:ring-2 focus:ring-[#164A41]" style="padding-left: 3.5rem;" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Username')" />
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 flex items-center text-stone-400" style="left: 1rem;">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M4 6h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" />
                        </svg>
                    </span>
                    <x-text-input id="email" class="block w-full rounded-xl border-0 bg-stone-100/70 !pl-14 pr-4 py-3 text-sm shadow-none placeholder:text-stone-400 transition focus:bg-white focus:ring-2 focus:ring-[#164A41]" style="padding-left: 3.5rem;" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Masukkan email" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-1" x-data="{ show: false }">
                    <span class="pointer-events-none absolute inset-y-0 flex items-center text-stone-400" style="left: 1rem;">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                        </svg>
                    </span>
                    <x-text-input id="password" class="block w-full rounded-xl border-0 bg-stone-100/70 !pl-14 pr-12 py-3 text-sm shadow-none placeholder:text-stone-400 transition focus:bg-white focus:ring-2 focus:ring-[#164A41]" style="padding-left: 3.5rem; padding-right: 3rem;" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="Masukkan password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600 focus:outline-none">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <div class="relative mt-1" x-data="{ show: false }">
                    <span class="pointer-events-none absolute inset-y-0 flex items-center text-stone-400" style="left: 1rem;">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                        </svg>
                    </span>
                    <x-text-input id="password_confirmation" class="block w-full rounded-xl border-0 bg-stone-100/70 !pl-14 pr-12 py-3 text-sm shadow-none placeholder:text-stone-400 transition focus:bg-white focus:ring-2 focus:ring-[#164A41]" style="padding-left: 3.5rem; padding-right: 3rem;" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600 focus:outline-none">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="mt-3 inline-flex w-full items-center justify-center rounded-xl px-5 py-3 text-sm font-black text-white shadow-sm transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-70" style="background: linear-gradient(135deg, #164A41, #4D774E);" x-bind:disabled="loading">
                Daftar
            </button>

            <p class="pt-2 text-center text-sm text-stone-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold hover:opacity-80" style="color: #164A41;">Login sekarang</a>
            </p>
        </form>
        </div>
    </div>
</x-guest-layout>
